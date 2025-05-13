<?php

namespace App\Tests\acceptance;

use Codeception\Util\HttpCode;
use Tests\AcceptanceTester;

class NewsApiCest
{
    public function testGetNews(AcceptanceTester $I): void
    {
        $I->sendGet('/news');
        $I->canSeeResponseCodeIs(HttpCode::OK);
    }

    public function testCreateEmptyNews(AcceptanceTester $I): void
    {
        $I->sendPost('/news', []);
        $I->canSeeResponseCodeIs(HttpCode::UNSUPPORTED_MEDIA_TYPE);
    }

    public function testCreateNews(AcceptanceTester $I): void
    {
        $I->sendPost('/news', ['url' => 'https://google.com']);
        $I->canSeeResponseCodeIs(HttpCode::OK);

        $newsId = $I->grabDataFromResponseByJsonPath('$.newsId')[0];
        $I->assertNotNull($newsId);
        $I->assertGreaterThan(0, $newsId);
    }

    public function testCreateReport(AcceptanceTester $I): void
    {
        $I->sendPost('/news/report', ['newsIds' => [1,2,3,4,5,6,7,8,9,10]]);
        $I->canSeeResponseCodeIs(HttpCode::OK);

        $I->seeResponseJsonMatchesJsonPath('$.reportUrl');
        $fileUrl = $I->grabDataFromResponseByJsonPath('$.reportUrl')[0];

        $I->sendGET($fileUrl);
        $I->canSeeResponseCodeIs(HttpCode::OK);
    }
}
