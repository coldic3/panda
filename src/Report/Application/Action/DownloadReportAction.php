<?php

declare(strict_types=1);

namespace Panda\Report\Application\Action;

use Panda\Report\Application\Exception\ReportHasNotBeenGeneratedYetException;
use Panda\Report\Domain\Repository\ReportRepositoryInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final readonly class DownloadReportAction
{
    public function __construct(
        private ReportRepositoryInterface $reportRepository,
        private string $projectDir,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        Assert::string($id = $request->get('id'));

        $report = $this->reportRepository->findById(Uuid::fromString($id));

        if (null === $report) {
            throw new NotFoundHttpException();
        }

        $filename = $report->getFile()->getFilename();

        if (null === $filename) {
            throw new ReportHasNotBeenGeneratedYetException($id);
        }

        $response = new BinaryFileResponse(sprintf('%s/private/reports/%s', $this->projectDir, $filename));
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);

        return $response;
    }
}
