<?php

namespace App;

use App\Entity\Agreement;
use App\Service\AgreementCheck\AgreementCheckService;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class AgreementCheckServiceTest
 * @package App
 */
class AgreementCheckServiceTest extends TestCase
{
    /**
     * @param array $agreements
     * @param Agreement $agreement
     * @param bool $result
     *
     * @dataProvider providerTestAgreement
     */
    public function testAgreement(array $agreements, Agreement $agreement, bool $result)
    {
        $overlapping = (new AgreementCheckService($agreements))
            ->getOverlapping($agreement);
        $canSaveAgreement = count($overlapping) === 0;
        $this->assertEquals($canSaveAgreement, $result);
    }

    /** @return array|array[] */
    public function providerTestAgreement(): array
    {
        return [
            [
                [],
                (new Agreement())->setId(1)->setDateStart(new DateTime('2024-06-01')),
                true
            ],
            [
                [
                    (new Agreement())->setId(1)->setDateStart(new DateTime('2022-01-01'))->setDateEnd(new DateTime('2022-12-31'))
                ],
                (new Agreement())->setId(2)->setDateStart(new DateTime('2023-03-01'))->setDateEnd(new DateTime('2023-05-31')),
                true
            ],
            [
                [
                    (new Agreement())->setId(1)->setDateStart(new DateTime('2022-01-01'))->setDateEnd(new DateTime('2022-12-31')),
                    (new Agreement())->setId(2)->setDateStart(new DateTime('2023-01-01'))->setDateEnd(new DateTime('2023-06-30'))
                ],
                (new Agreement())->setId(3)->setDateStart(new DateTime('2023-03-01'))->setDateEnd(new DateTime('2023-12-31')),
                false
            ],
            [
                [
                    (new Agreement())->setId(1)->setDateStart(new DateTime('2022-01-01'))->setDateEnd(new DateTime('2022-12-31')),
                    (new Agreement())->setId(2)->setDateStart(new DateTime('2024-01-01'))->setDateEnd(new DateTime('2024-06-30'))
                ],
                (new Agreement())->setId(3)->setDateStart(new DateTime('2023-03-01'))->setDateEnd(new DateTime('2023-12-31')),
                true
            ],
            [
                [
                    (new Agreement())->setId(1)->setDateStart(new DateTime('2022-01-01'))->setDateEnd(new DateTime('2022-12-31')),
                    (new Agreement())->setId(2)->setDateStart(new DateTime('2024-01-01'))->setDateEnd(new DateTime('2024-06-30'))
                ],
                (new Agreement())->setId(3)->setDateStart(new DateTime('2023-03-01')),
                false
            ],
            [
                [
                    (new Agreement())->setId(1)->setDateStart(new DateTime('2022-01-01'))->setDateEnd(new DateTime('2022-12-31')),
                    (new Agreement())->setId(2)->setDateStart(new DateTime('2023-01-01'))->setDateEnd(new DateTime('2023-06-30'))
                ],
                (new Agreement())->setId(2)->setDateStart(new DateTime('2023-03-01'))->setDateEnd(new DateTime('2023-12-31')),
                true
            ],
            [
                [
                    (new Agreement())->setId(1)->setDateStart(new DateTime('2022-01-01'))->setDateEnd(new DateTime('2022-12-31')),
                    (new Agreement())->setId(2)->setDateStart(new DateTime('2023-01-01'))->setDateEnd(new DateTime('2023-06-30'))
                ],
                (new Agreement())->setId(3)->setDateStart(new DateTime('2023-03-01'))->setDateEnd(new DateTime('2023-12-31')),
                false
            ],
            [
                [
                    (new Agreement())->setId(1)->setDateStart(new DateTime('2022-01-01'))->setDateEnd(new DateTime('2022-12-31')),
                    (new Agreement())->setId(2)->setDateStart(new DateTime('2023-01-01'))->setDateEnd(new DateTime('2023-06-30'))
                ],
                (new Agreement())->setId(2)->setDateStart(new DateTime('2022-11-30'))->setDateEnd(new DateTime('2023-12-31')),
                false
            ],

            // timeless
            [
                [
                    (new Agreement())->setId(1)->setDateStart(new DateTime('2022-01-01'))
                ],
                (new Agreement())->setId(2)->setDateStart(new DateTime('2022-11-30'))->setDateEnd(new DateTime('2023-12-31')),
                false
            ],
            [
                [
                    (new Agreement())->setId(1)->setDateStart(new DateTime('2022-01-01'))
                ],
                (new Agreement())->setId(2)->setDateStart(new DateTime('2021-01-31')),
                false
            ],
            [
                [
                    (new Agreement())->setId(1)->setDateStart(new DateTime('2022-01-01'))->setDateEnd(new DateTime('2022-12-31'))
                ],
                (new Agreement())->setId(2)->setDateStart(new DateTime('2022-12-31')),
                false
            ],
            [
                [
                    (new Agreement())->setId(1)->setDateStart(new DateTime('2022-01-01'))->setDateEnd(new DateTime('2022-12-31'))
                ],
                (new Agreement())->setId(2)->setDateStart(new DateTime('2023-01-01')),
                true
            ],
        ];
    }
}