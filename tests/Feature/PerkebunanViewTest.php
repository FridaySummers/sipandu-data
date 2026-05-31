<?php

namespace Tests\Feature;

use Tests\TestCase;

class PerkebunanViewTest extends TestCase
{
    public function test_view_renders_and_contains_export_headers_2025_2029(): void
    {
        $html = view('dinas.perkebunan')->render();
        $this->assertStringContainsString('perkebunan-populasi.csv', $html);
        $this->assertStringContainsString('"2025","2026","2027","2028","2029"', $html);
    }

    public function test_fetch_url_for_edit_is_valid(): void
    {
        $html = view('dinas.perkebunan')->render();
        $this->assertStringContainsString("fetch('/perkebunan/prod/'+prodEditId,{", $html);
    }
}
