<?php

namespace MediaMonks\SonataMediaBundle\Tests\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MediaMonks\SonataMediaBundle\Entity\Media as BaseMedia;

/**
 * @ORM\Entity(repositoryClass="MediaMonks\SonataMediaBundle\Repository\MediaRepository")
 * @ORM\Table
 */
class Media extends BaseMedia
{
}
