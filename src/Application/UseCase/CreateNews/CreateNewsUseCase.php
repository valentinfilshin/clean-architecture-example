<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateNews;

use App\Application\UseCase\CreateNews\Input\CreateNewsRequest;
use App\Domain\Entity\News;
use App\Domain\Repository\NewsRepositoryInterface;
use App\Domain\ValueObject\Title;
use App\Domain\ValueObject\Url;
use DateTimeImmutable;

readonly class CreateNewsUseCase
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository
    )
    {
    }

    public function __invoke(CreateNewsRequest $request): int
    {
        $url = new Url($request->url);
        $title = new Title($this->getTitleFromUrl($url->url));
        $data = new DateTimeImmutable();

        $news = new News($title, $url, $data);
        return $this->newsRepository->save($news);
    }

    public function getTitleFromUrl(string $url): ?string
    {
        // Реализация метода из примера выше
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        $html = curl_exec($ch);
        curl_close($ch);

        if (!$html) {
            return null;
        }

        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $titleTags = $doc->getElementsByTagName('title');

        if ($titleTags->length > 0) {
            return trim($titleTags->item(0)->textContent);
        }

        return null;
    }
}