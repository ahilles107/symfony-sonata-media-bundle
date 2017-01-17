<?php

namespace MediaMonks\MediaBundle\Helper;

use MediaMonks\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class Controller
{
    /**
     * @var Parameter
     */
    protected $parameterHelper;

    /**
     * @var Thumbnail
     */
    protected $thumbnailHelper;

    /**
     * @var string
     */
    protected $mediaBaseUrl;

    /**
     * Controller constructor.
     * @param Parameter $parameterHelper
     * @param Thumbnail $thumbnail
     * @param $mediaBaseUrl
     */
    public function __construct(Parameter $parameterHelper, Thumbnail $thumbnail, $mediaBaseUrl)
    {
        $this->parameterHelper = $parameterHelper;
        $this->thumbnailHelper = $thumbnail;
        $this->mediaBaseUrl = $mediaBaseUrl;
    }

    /**
     * @param Request $request
     * @param $id
     * @param callable $converter
     * @return RedirectResponse
     */
    public function redirectToThumbnail(Request $request, $id, callable $converter)
    {
        $parameters = $this->verifyParameters($request, $id);
        try {
            $media = $converter($id);
        }
        catch(\Exception $e) {
            throw new NotFoundHttpException('media_not_found');
        }
        return $this->handleCreationAndRedirect($media, $parameters);
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     * @throws BadRequestHttpException
     */
    protected function verifyParameters(Request $request, $id)
    {
        $parameters = $request->query->all();
        if(!$this->parameterHelper->isValid($parameters + ['id' => $id])) {
            throw new BadRequestHttpException('invalid_signature');
        }
        return $parameters;
    }

    /**
     * @param MediaInterface $media
     * @param $parameters
     * @return RedirectResponse
     */
    protected function handleCreationAndRedirect(MediaInterface $media, $parameters)
    {
        $source = $media->getImage();
        $destination = $this->parameterHelper->getDestinationFilename($source, $parameters);

        try {
            $this->thumbnailHelper
                ->createIfNotExists($source, $destination, $media->getDefaultImageOptions() + $parameters);
        }
        catch(\Exception $e) {
            throw new ServiceUnavailableHttpException(60, 'could_not_create_thumbnail', $e);
        }

        $cacheTtl = 60 * 60 * 24 * 90; // 90 days
        $response = new RedirectResponse($this->mediaBaseUrl . $destination);
        $response->setSharedMaxAge($cacheTtl);
        $response->setMaxAge($cacheTtl);
        return $response;
    }
}