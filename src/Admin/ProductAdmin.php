<?php

namespace App\Admin;

use App\Entity\Product;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $form
     * @return void
     */
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->with('General')
                ->add('title', TextType::class)
                ->add('category', ModelType::class, [
                    'property' => 'name',
                ])
                ->add('description', TextareaType::class, [
                    'required' => false,
                ])
                ->add('price', MoneyType::class, [
                    'currency' => 'UAH',
                ])
            ->end()
            ->with('Upload Images')
                ->add('images', ModelType::class, [
                    'property' => 'title',
                    'multiple' => true,
                ])
            ->end()
        ;
    }

    /**
     * @param ErrorElement $errorElement
     * @param Product $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('title')
                ->assertRequired()
                ->assertLength(['max' => 255])
            ->end()
            ->with('description')
                ->assertLength(['max' => 1000])
            ->end()
            ->with('price')
                ->assertRequired()
                ->assertRange(['min' => 0, 'max' => 9999999])
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
        ;
    }

    /**
     * @param ListMapper $list
     * @return void
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('title')
            ->add('category', ModelType::class, [
                'associated_property' => 'name',
            ])
            ->add('created_at', 'date', [
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
            ->add('description')
            ->add('price', MoneyType::class)
        ;
    }

    /**
     * @param Product $object
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