<?php


namespace App\Listener;


use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber
{
    /**
     * @var CacheManager
     */
    private $cacheManager;
    /**
     * @var UploaderHelper
     */
    private $helper;

    /**
     * ImageCacheSubscriber constructor.
     * @param CacheManager $cacheManager
     * @param UploaderHelper $helper
     */
    public function __construct(CacheManager $cacheManager, UploaderHelper $helper)
    {

        $this->cacheManager = $cacheManager;
        $this->helper = $helper;
    }


    /**
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return [
            'preRemove',
            'preUpdate'
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $args->getEntityManager();
        $entity = $args->getEntity();
        if (!$args->getEntity() instanceof Property) {
            return;
        }
        $this->cacheManager->remove($this->helper->asset($entity, 'imageFile'));
    }


    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $args->getEntityManager();
        $entity = $args->getEntity();
        if (!$args->getEntity() instanceof Property) {
            return;
        }
        if ($args->getObject()->getImageFile() instanceof UploadedFile) {
            $this->cacheManager->remove($this->helper->asset($entity, 'imageFile'));
        }
    }
}