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
}
