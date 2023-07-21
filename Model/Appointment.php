<?php

namespace DanielNavarro\ChatGptReviewValidator\Model;

use FarmaciasVivo\Appointments\Api\Data\AppointmentInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class Appointment extends AbstractExtensibleModel implements AppointmentInterface
{
    public const APPOINTMENT_STATUS_PENDING = 'pending';
    public const APPOINTMENT_STATUS_CONFIRMED = 'confirmed';
    public const APPOINTMENT_STATUS_CANCELED = 'canceled';

    public const APPOINTMENT_DURATION_MINUTES = 30;
    public const APPOINTMENT_DAY_MONDAY    = 0b1000000;
    public const APPOINTMENT_DAY_TUESDAY   = 0b0100000;
    public const APPOINTMENT_DAY_WEDNESDAY = 0b0010000;
    public const APPOINTMENT_DAY_THURSDAY  = 0b0001000;
    public const APPOINTMENT_DAY_FRIDAY    = 0b0000100;
    public const APPOINTMENT_DAY_SATURDAY  = 0b0000010;
    public const APPOINTMENT_DAY_SUNDAY    = 0b0000001;
    public const APPOINTMENT_DAY_ALL       = 0b1111111;

    protected function _construct()
    {
        $this->_init(\FarmaciasVivo\Appointments\Model\ResourceModel\Appointment::class);
        $this->setIdFieldName('id');
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->getData(self::ID);
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @return string
     */
    public function getDateTime(): string
    {
        return $this->getData(self::DATE_TIME);
    }

    /**
     * @return int
     */
    public function getQuoteId(): int
    {
        return $this->getData(self::QUOTE_ID);
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->getData(self::FIRSTNAME);
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->getData(self::LASTNAME);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->getData(self::PHONE);
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @return int
     */
    public function getReminderSent(): int
    {
        return $this->getData(self::REMINDER_SENT);
    }

    /**
     * @return string
     */
    public function getRewardCoupon(): string
    {
        return $this->getData(self::REWARD_COUPON);
    }

    /**
     * @return int
     */
    public function getRewardSent(): int
    {
        return $this->getData(self::REWARD_SENT);
    }

    /**
     * @param string $hostId
     * @return AppointmentInterface
     */
    public function setDateTime(string $dateTime): AppointmentInterface
    {
        return $this->setData(self::DATE_TIME, $dateTime);
    }

    /**
     * @param int $quoteId
     * @return AppointmentInterface
     */
    public function setQuoteId(int $quoteId): AppointmentInterface
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }

    /**
     * @param int $orderId
     * @return AppointmentInterface
     */
    public function setOrderId(int $orderId): AppointmentInterface
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * @param string $firstname
     * @return AppointmentInterface
     */
    public function setFirstname(string $firstname): AppointmentInterface
    {
        return $this->setData(self::FIRSTNAME, $firstname);
    }

    /**
     * @param string $lastname
     * @return AppointmentInterface
     */
    public function setLastname(string $lastname): AppointmentInterface
    {
        return $this->setData(self::LASTNAME, $lastname);
    }

    /**
     * @param string $email
     * @return AppointmentInterface
     */
    public function setEmail(string $email): AppointmentInterface
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * @param string $phone
     * @return AppointmentInterface
     */
    public function setPhone(string $phone): AppointmentInterface
    {
        return $this->setData(self::PHONE, $phone);
    }

    /**
     * @param string $status
     * @return AppointmentInterface
     */
    public function setStatus(string $status): AppointmentInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @param int $reminderSent
     * @return AppointmentInterface
     */
    public function setReminderSent(int $reminderSent): AppointmentInterface
    {
        return $this->setData(self::REMINDER_SENT, $reminderSent);
    }

    /**
     * @param string $coupon
     * @return AppointmentInterface
     */
    public function setRewardCoupon(string $coupon): AppointmentInterface
    {
        return $this->setData(self::REWARD_COUPON, $coupon);
    }

    /**
     * @param int $rewardCoupon
     * @return AppointmentInterface
     */
    public function setRewardSent(int $rewardCoupon): AppointmentInterface
    {
        return $this->setData(self::REWARD_SENT, $rewardCoupon);
    }
}
