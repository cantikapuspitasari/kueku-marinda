<?php

namespace App\Support;

use RuntimeException;
use ZipArchive;

/**
 * Penulis file Excel (.xlsx) sederhana tanpa library tambahan.
 * Hanya membutuhkan ekstensi PHP "zip" (sudah ikut di XAMPP / Laravel).
 *
 * Contoh:
 *   $x = new XlsxWriter();
 *   $x->tambahSheet('Ringkasan', [
 *       [XlsxWriter::sel('Judul', 'judul')],
 *       [XlsxWriter::sel('Nama', 'header'), XlsxWriter::sel('Total', 'header')],
 *       ['Kue', XlsxWriter::sel(15000, 'rupiah')],
 *   ], [30, 18]);
 *   $path = $x->simpan();
 */
class XlsxWriter
{
    // Nama gaya => nomor xf pada styles.xml di bawah
    private const GAYA = [
        'judul'        => 1,
        'header'       => 2,
        'teks'         => 3,
        'angka'        => 4,
        'rupiah'       => 5,
        'tebal'        => 6,
        'teks_tebal'   => 7,
        'angka_tebal'  => 8,
        'rupiah_tebal' => 9,
        'persen'       => 10,
        'persen_tebal' => 11,
    ];

    /** @var array<int, array{nama:string, baris:array, lebar:array}> */
    private array $sheets = [];

    /** Sel dengan gaya tertentu. */
    public static function sel($nilai, string $gaya): array
    {
        return ['v' => $nilai, 's' => $gaya];
    }

    /**
     * @param string $nama  Nama sheet (maks. 31 karakter)
     * @param array  $baris Daftar baris; tiap baris = daftar sel (nilai biasa atau hasil sel())
     * @param array  $lebar Lebar tiap kolom (satuan karakter)
     */
    public function tambahSheet(string $nama, array $baris, array $lebar = []): static
    {
        $nama = preg_replace('/[\\\\\/\?\*\[\]:]/', ' ', $nama);
        $this->sheets[] = ['nama' => mb_substr($nama, 0, 31), 'baris' => $baris, 'lebar' => $lebar];

        return $this;
    }

    /** Menulis workbook ke file sementara dan mengembalikan path-nya. */
    public function simpan(): string
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('Ekstensi PHP "zip" belum aktif. Aktifkan extension=zip di php.ini.');
        }
        if (empty($this->sheets)) {
            throw new RuntimeException('Workbook belum punya sheet.');
        }

        $path = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new ZipArchive();
        if ($zip->open($path, ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Gagal membuat file Excel sementara.');
        }

        $zip->addFromString('[Content_Types].xml', $this->contentTypes());
        $zip->addFromString('_rels/.rels', $this->relsUtama());
        $zip->addFromString('xl/workbook.xml', $this->workbook());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->relsWorkbook());
        $zip->addFromString('xl/styles.xml', $this->styles());
        foreach ($this->sheets as $i => $sheet) {
            $zip->addFromString('xl/worksheets/sheet' . ($i + 1) . '.xml', $this->sheetXml($sheet));
        }
        $zip->close();

        return $path;
    }

    // ------------------------------------------------------------------

    private const XML = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";

    private function contentTypes(): string
    {
        $o = self::XML . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>';
        foreach ($this->sheets as $i => $_) {
            $o .= '<Override PartName="/xl/worksheets/sheet' . ($i + 1) . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
        }

        return $o . '</Types>';
    }

    private function relsUtama(): string
    {
        return self::XML . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
    }

    private function workbook(): string
    {
        $o = self::XML . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets>';
        foreach ($this->sheets as $i => $s) {
            $o .= '<sheet name="' . $this->esc($s['nama']) . '" sheetId="' . ($i + 1) . '" r:id="rId' . ($i + 1) . '"/>';
        }

        return $o . '</sheets></workbook>';
    }

    private function relsWorkbook(): string
    {
        $o = self::XML . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';
        foreach ($this->sheets as $i => $_) {
            $o .= '<Relationship Id="rId' . ($i + 1) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . ($i + 1) . '.xml"/>';
        }
        $o .= '<Relationship Id="rId' . (count($this->sheets) + 1) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>';

        return $o . '</Relationships>';
    }

    private function styles(): string
    {
        $garis = '<left style="thin"><color rgb="FFD9D2C5"/></left><right style="thin"><color rgb="FFD9D2C5"/></right>'
            . '<top style="thin"><color rgb="FFD9D2C5"/></top><bottom style="thin"><color rgb="FFD9D2C5"/></bottom><diagonal/>';

        // Urutan xf harus sama dengan konstanta GAYA di atas.
        $xf = [
            '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>',                                                         // 0 default
            '<xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>',                                           // 1 judul
            '<xf numFmtId="0" fontId="2" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">'
                . '<alignment horizontal="center" vertical="center" wrapText="1"/></xf>',                                             // 2 header
            '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1"><alignment vertical="top" wrapText="1"/></xf>', // 3 teks
            '<xf numFmtId="3" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyBorder="1"/>',                   // 4 angka
            '<xf numFmtId="164" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyBorder="1"/>',                 // 5 rupiah
            '<xf numFmtId="0" fontId="3" fillId="0" borderId="0" xfId="0" applyFont="1"/>',                                           // 6 tebal
            '<xf numFmtId="0" fontId="3" fillId="3" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1"/>',             // 7 teks tebal
            '<xf numFmtId="3" fontId="3" fillId="3" borderId="1" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1"/>',   // 8 angka tebal
            '<xf numFmtId="164" fontId="3" fillId="3" borderId="1" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1"/>', // 9 rupiah tebal
            '<xf numFmtId="10" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyBorder="1"/>',                  // 10 persen
            '<xf numFmtId="10" fontId="3" fillId="3" borderId="1" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1"/>', // 11 persen tebal
        ];

        return self::XML . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<numFmts count="1"><numFmt numFmtId="164" formatCode="&quot;Rp &quot;#,##0"/></numFmts>'
            . '<fonts count="4">'
            . '<font><sz val="11"/><name val="Calibri"/></font>'
            . '<font><b/><sz val="14"/><name val="Calibri"/></font>'
            . '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            . '<font><b/><sz val="11"/><name val="Calibri"/></font>'
            . '</fonts>'
            . '<fills count="4">'
            . '<fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF993A3C"/><bgColor indexed="64"/></patternFill></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFF6F3EE"/><bgColor indexed="64"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="2"><border><left/><right/><top/><bottom/><diagonal/></border><border>' . $garis . '</border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="' . count($xf) . '">' . implode('', $xf) . '</cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>';
    }

    private function sheetXml(array $sheet): string
    {
        $o = self::XML . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetViews><sheetView workbookViewId="0"/></sheetViews>'
            . '<sheetFormatPr defaultRowHeight="15"/>';

        if (! empty($sheet['lebar'])) {
            $o .= '<cols>';
            foreach (array_values($sheet['lebar']) as $i => $w) {
                $o .= '<col min="' . ($i + 1) . '" max="' . ($i + 1) . '" width="' . (float) $w . '" customWidth="1"/>';
            }
            $o .= '</cols>';
        }

        $o .= '<sheetData>';
        foreach (array_values($sheet['baris']) as $r => $baris) {
            $nomorBaris = $r + 1;
            $o .= '<row r="' . $nomorBaris . '">';
            foreach (array_values($baris) as $c => $sel) {
                $o .= $this->selXml($this->kolom($c) . $nomorBaris, $sel);
            }
            $o .= '</row>';
        }

        return $o . '</sheetData></worksheet>';
    }

    private function selXml(string $ref, $sel): string
    {
        $nilai = $sel;
        $gaya = 0;
        if (is_array($sel) && array_key_exists('v', $sel)) {
            $nilai = $sel['v'];
            $gaya = self::GAYA[$sel['s'] ?? ''] ?? 0;
        }

        $atribut = ' r="' . $ref . '"' . ($gaya ? ' s="' . $gaya . '"' : '');

        if ($nilai === null || $nilai === '') {
            return $gaya ? '<c' . $atribut . '/>' : '';
        }
        if (is_bool($nilai)) {
            $nilai = $nilai ? 'Ya' : 'Tidak';
        }
        if (is_int($nilai) || is_float($nilai)) {
            return '<c' . $atribut . '><v>' . $nilai . '</v></c>';
        }

        return '<c' . $atribut . ' t="inlineStr"><is><t xml:space="preserve">' . $this->esc((string) $nilai) . '</t></is></c>';
    }

    /** 0 => A, 25 => Z, 26 => AA ... */
    private function kolom(int $i): string
    {
        $huruf = '';
        for ($i++; $i > 0; $i = intdiv($i - 1, 26)) {
            $huruf = chr(65 + ($i - 1) % 26) . $huruf;
        }

        return $huruf;
    }

    private function esc(string $s): string
    {
        // Buang karakter kontrol yang tidak valid di XML, lalu escape.
        $s = preg_replace('/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', $s) ?? '';

        return htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
