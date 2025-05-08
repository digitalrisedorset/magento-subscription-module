<?php

namespace Drd\Subscribe\Model\Payment;

use Magento\Sales\Model\Order;
use Magento\Vault\Model\PaymentTokenManagement;
use Psr\Log\LoggerInterface;

class TokenReader
{
    private LoggerInterface $logger;
    private PaymentTokenManagement $paymentTokenManagement;

    /**
     * @param PaymentTokenManagement $paymentTokenManagement
     * @param LoggerInterface $logger
     */
    public function __construct(
        PaymentTokenManagement    $paymentTokenManagement,
        LoggerInterface           $logger
    ) {
        $this->logger = $logger;
        $this->paymentTokenManagement = $paymentTokenManagement;
    }

    /**
     * @param Order $order
     * @return string|null
     */
    public function getPaymentToken(Order $order): ?string
    {
        $payment = $order->getPayment();
        $publicHash = $payment->getAdditionalInformation('public_hash');
        $customerId = $order->getCustomerId();

        if (!$publicHash || !$customerId) {
            $this->logger->error('Missing public_hash or customer ID; cannot retrieve Braintree token.');
            return null;
        }

        try {
            $vaultToken = $this->paymentTokenManagement->getByPublicHash(
                $publicHash,
                $customerId
            );

            if (!$vaultToken || !$vaultToken->getGatewayToken()) {
                $this->logger->error('Vault token lookup failed or gateway token missing for public_hash: ' . $publicHash);
                return null;
            }

            $gatewayToken = $vaultToken->getGatewayToken();
        } catch (\Exception $e) {
            $this->logger->error('Error loading vault token: ' . $e->getMessage());
        }


        return $gatewayToken;
    }
}
