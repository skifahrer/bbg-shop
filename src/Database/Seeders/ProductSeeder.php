<?php

namespace App\Database\Seeders;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductSeeder extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $csvData = $this->readCsvData('./seeds/Products.csv');

        foreach ($csvData as $row) {
            $product = new Product();
            $product->setItem($row['ITEM']);
            $product->setOxprice(floatval($row['OXPRICE']));
            $product->setWeight(floatval($row['WEIGHT']));
            $product->setStock(intval($row['STOCK']));
            $product->setLength(intval($row['LENGTH']));
            $product->setWidth(intval($row['WIDTH']));
            $product->setHeight(intval($row['HEIGHT']));
            $product->setTitleEn($row['TITLE_EN']);
            $product->setTitleSk($row['TITLE_SK']);
            $product->setTitleSl($row['TITLE_SL']);
            $product->setTitleHu($row['TITLE_HU']);
            $product->setTitleHr($row['TITLE_HR']);
            $product->setTitleRo($row['TITLE_RO']);
            $product->setTitleBg($row['TITLE_BG']);
            $product->setFeaturesJson(json_decode($row['FEATURES_JSON'], true));

            $manager->persist($product);
        }

        $manager->flush();
    }

    private function readCsvData(string $filePath): array
    {
        $data = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Skip the header row
            fgetcsv($handle);

            while (($row = fgetcsv($handle)) !== false) {
                $data[] = [
                    'ITEM' => $row[0],
                    'OXPRICE' => $row[1],
                    'WEIGHT' => $row[2],
                    'STOCK' => $row[3],
                    'LENGTH' => $row[4],
                    'WIDTH' => $row[5],
                    'HEIGHT' => $row[6],
                    'TITLE_EN' => $row[7],
                    'TITLE_SK' => $row[8],
                    'TITLE_SL' => $row[9],
                    'TITLE_HU' => $row[10],
                    'TITLE_HR' => $row[11],
                    'TITLE_RO' => $row[12],
                    'TITLE_BG' => $row[13],
                    'FEATURES_JSON' => $row[14],
                ];
            }
            fclose($handle);
        }

        return $data;
    }
}
