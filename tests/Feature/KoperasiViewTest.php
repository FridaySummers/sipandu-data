<?php

namespace Tests\Feature;

use Tests\TestCase;

class KoperasiViewTest extends TestCase
{
    public function test_view_renders_and_contains_core_elements(): void
    {
        $html = view('dinas.koperasi')->render();
        $this->assertStringContainsString('Ajukan Data', $html);
        $this->assertStringContainsString('id="kop-tbody"', $html);
    }

    public function test_delete_url_syntax_is_valid(): void
    {
        $html = view('dinas.koperasi')->render();
        $this->assertStringContainsString("fetch('/opd/rows/'+id,{method:'DELETE'", $html);
    }

    public function test_submit_sets_status_menunggu(): void
    {
        $html = view('dinas.koperasi')->render();
        $this->assertStringContainsString("dmStatuses[ura]='Menunggu Persetujuan'", $html);
    }
}
