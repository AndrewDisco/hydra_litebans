<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once './inc/page.php';

final class PageTest extends TestCase {
    public function testBansPagerHTML(): void {
        $page = new Page("bans", false, false);

        foreach (range(1, 2) as $currentPage) {
            $page->page = $currentPage;
            $pager = $page->generate_pager(10);
            $this->assertIsArray($pager);
            $this->assertCount(3, $pager);
            $this->assertStringContainsString("Page $currentPage/2", $pager["count"]);
        }
    }

    public function testHistoryPagerHTML(): void {
        $page = new Page("test", false);
        foreach (explode("\n", file_get_contents("./inc/test/php/test_setup.sql")) as $query) {
            if (strlen($query) > 0) {
                $page->conn->query($query);
            }
        }
        $_GET = ["uuid" => "2ccd0bb281214361803a945b8f0644ab"];
        ob_start();
        require_once './history.php';
        $output = ob_get_clean();
        $historyPager = 'Page 1/1';
        $this->assertStringContainsString($historyPager, $output);
    }
}
