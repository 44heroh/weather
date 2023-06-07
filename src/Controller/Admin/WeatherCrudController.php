<?php

namespace App\Controller\Admin;

use App\Entity\Weather;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class WeatherCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Weather::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('date'),
            NumberField::new('temperature')->setNumDecimals(2),
            NumberField::new('clouds'),
            AssociationField::new('city', "Город")
                ->autocomplete()
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Your Entity')
            ->setEntityLabelInPlural('Your Entities');
    }

}
