<?php
/**
 * Created by PhpStorm.
 * User: subesz
 * Date: 2020. 01. 24.
 * Time: 1:04
 */

namespace Tests\Unit;

use App\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    /**
     * Alap eset, ahogy a legtöbben megadják
     */
    public function testGetPhoneAttributeWithCorrectNumber()
    {
        // 1. Kapjuk meg a 06-osból a formázott verziót
        $customer = new Customer();
        $customer->phone = '06308266701';

        $this->assertSame('06 30 826 6701', $customer->phone);
    }

    /**
     * +36 lekezelése
     */
    public function testGetPhoneAttributeWithInternational()
    {
        // 1. Kapjuk meg a 06-osból a formázott verziót
        $customer = new Customer();
        $customer->phone = '36308266701';

        $this->assertSame('06 30 826 6701', $customer->phone);
    }

    /**
     * Hiányzó előtag lekezelése
     */
    public function testGetPhoneAttributeWithoutPrefix()
    {
        // 1. Kapjuk meg a 06-osból a formázott verziót
        $customer = new Customer();
        $customer->phone = '308266701';

        $this->assertSame('06 30 826 6701', $customer->phone);
    }

    /**
     * Hiányzó előtag lekezelése ha Budapesti
     */
    public function testGetPhoneAttributeWithoutPrefixBudapest()
    {
        // 1. Kapjuk meg a 06-osból a formázott verziót
        $customer = new Customer();
        $customer->phone = '18266701';

        $this->assertSame('06 1 826 6701', $customer->phone);
    }
}
