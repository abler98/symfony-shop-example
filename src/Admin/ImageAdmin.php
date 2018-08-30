<?php

namespace App\Admin;

use App\Entity\Image;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ImageAdmin extends AbstractAdmin
{
    /**
     * @var string
     */
    private $storagePath;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * ImageAdmin constructor.
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     * @param string $storagePath
     */
    public function __construct(string $code, string $class, string $baseControllerName, string $storagePath)
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->storagePath = $storagePath;
        $this->filesystem = new Filesystem();
    }

    /**
     * @param FormMapper $form
     * @return void
     */
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('title', TextType::class, [
                'required' => false,
            ])
            ->add('file', FileType::class)
        ;
    }

    /**
     * @param ErrorElement $errorElement
     * @param Image $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('title')
                ->assertLength(['max' => 255])
            ->end()
            ->with('file')
                ->assertRequired()
                ->assertImage(['maxSize' => 1024 * 1024 * 5])
            ->end()
        ;
    }

    /**
     * @param DatagridMapper $filter
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('title')
            ->add('filename')
        ;
    }

    /**
     * @param ListMapper $list
     * @return void
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('title')
            ->add('filename')
            ->add('created_at', 'datetime', [
                'timezone' => 'Europe/Kiev',
                'sortable' => true,
            ])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    /**
     * @param ShowMapper $show
     * @return void
     */
    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->add('title')
            ->add('webPath', null, [
                'template' => 'admin/show_image.html.twig',
                'label' => 'Preview',
            ])
        ;
    }

    /**
     * @param Image $image
     * @return void
     */
    public function prePersist($image)
    {
        $file = $image->getFile();
        $image->setFile(null);

        if (null === $file) {
            return;
        }

        if (null === $image->getTitle()) {
            $image->setTitle($file->getClientOriginalName());
        }

        $image->setFilename(
            sprintf('%s-%s.%s', md5_file($file->getRealPath()), uniqid(), $file->getClientOriginalExtension())
        );

        if (!$this->filesystem->exists($image->getPathname($this->storagePath))) {
            $file->move($this->storagePath, $image->getFilename());
        }
    }

    /**
     * @param Image $image
     * @return void
     */
    public function preRemove($image)
    {
        if (null === $image->getFilename()) {
            return;
        }

        $pathname = $image->getPathname($this->storagePath);

        if ($this->filesystem->exists($pathname)) {
            $this->filesystem->remove($pathname);
        }
    }

    /**
     * @param Image $object
     * @return string
     */
    public function toString($object)
    {
        if (null !== $object->getTitle()) {
            return $object->getTitle();
        }

        return parent::toString($object);
    }
}