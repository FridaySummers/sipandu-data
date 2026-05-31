<?php

namespace Tests\Feature;

use Tests\TestCase;

class DpmptspViewTest extends TestCase
{
    public function test_view_renders_and_uses_dataset_for_opd_name(): void
    {
        $html = view('dinas.dpmptsp')->render();
        $this->assertStringContainsString('id="dpmptsp-page"', $html);
        $this->assertStringContainsString('data-opd-name="', $html);
        $this->assertStringContainsString('dataset.opdName', $html);
    }
}
