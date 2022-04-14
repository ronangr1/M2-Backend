<?php declare(strict_types=1);
/**
 * Copyright © Ronan Guérin. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ronangr1\Backend\Plugin\App\Request;

use Magento\Backend\App\Request\BackendValidator;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Form\FormKey;

class BackendValidatorPlugin
{
    private FormKey $formKey;
    private Session $authSession;

    /**
     * @param FormKey $formKey
     * @param Session $authSession
     */
    public function __construct(
        FormKey $formKey,
        Session $authSession
    )
    {
        $this->formKey = $formKey;
        $this->authSession = $authSession;
    }

    /**
     * @param BackendValidator $subject
     * @param RequestInterface $request
     * @param ActionInterface $action
     * @return BackendValidator
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundValidate(BackendValidator $subject, \Closure $proceed, RequestInterface $request, ActionInterface $action): ?BackendValidator
    {
        if(!$request->getParam('form_key') && $this->authSession->isLoggedIn()) {
            $request->setParams([
                'form_key' => $this->formKey->getFormKey()
            ]);
        }

        return $proceed($request, $action);
    }
}
