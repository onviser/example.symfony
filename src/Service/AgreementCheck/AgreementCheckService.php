<?php declare(strict_types=1);

namespace App\Service\AgreementCheck;

use App\Entity\Agreement;

/**
 * Class AgreementCheckService
 * @package App\Service\AgreementCheck
 */
class AgreementCheckService
{
    /** @var Agreement[]|array */
    private array $agreements = [];

    /**
     * AgreementCheckService constructor.
     * @param array $agreements
     */
    public function __construct(array $agreements)
    {
        $this->agreements = $agreements;
    }

    /**
     * @param Agreement $agreement
     * @return array
     */
    public function getOverlapping(Agreement $agreement): array
    {
        // no agreements for this property
        if (count($this->agreements) === 0) {
            return [];
        }

        $overlapping = [];
        foreach ($this->agreements as $item) {

            // this is a same agreement, skip
            if ($item->getId() === $agreement->getId()) {
                continue;
            }

            // compared agreements has date end
            if ($item->getDateEnd() && $agreement->getDateEnd()) {

                // agreement does not overlap
                if (($item->getDateStart() > $agreement->getDateEnd()) || ($item->getDateEnd() < $agreement->getDateStart())) {
                    continue;
                }

                $overlapping[] = $item;
                continue;
            } elseif ($item->getDateEnd() && !$agreement->getDateEnd()) { // one of them timeless

                // agreement does not overlap
                if ($agreement->getDateStart() > $item->getDateEnd()) {
                    continue;
                }

                $overlapping[] = $item;
                continue;
            } elseif (!$item->getDateEnd() && $agreement->getDateEnd()) { // one of them timeless

                // agreement does not overlap
                if ($item->getDateStart() > $agreement->getDateEnd()) {
                    continue;
                }

                $overlapping[] = $item;
                continue;
            } else {
                $overlapping[] = $item;
                continue;
            }
        }

        return $overlapping;
    }
}