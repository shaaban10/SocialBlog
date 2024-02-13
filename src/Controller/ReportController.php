<?php
namespace App\Controller;

use App\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    #[Route('/report-account', name: 'report_account', methods: ['POST'])]
    public function reportAccount(Request $request,Account $accountId): Response
    {
        // Handle the report account functionality here
        $accountId = $request->request->get('accountId');

        // Example: Report handling logic
        // You can customize this according to your application's requirements
        // For demonstration, we're just returning a success message
        return $this->json(['message' => 'Account reported successfully']);
    }
}
