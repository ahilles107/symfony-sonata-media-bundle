<?php

namespace MediaMonks\SonataMediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MediaController extends Controller
{
    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function imageAction(Request $request, $id)
    {
        $media = $this->getDoctrine()->getManager()->find('MediaMonksSonataMediaBundle:Media', $id);

        return $this->get('mediamonks.sonata_media.helper.redirect_helper')->redirectToMediaImage($media, $request, [
            'fit' => 'crop'
        ]);
    }
}
