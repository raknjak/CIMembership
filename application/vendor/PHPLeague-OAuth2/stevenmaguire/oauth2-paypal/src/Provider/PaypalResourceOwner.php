<?php namespace Stevenmaguire\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class PaypalResourceOwner implements ResourceOwnerInterface
{
    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array  $response
     */
    public function __construct(array $response = array())
    {
        $this->response = $response;
    }

    /**
     * Get user id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->response['user_id'] ?: null;
    }

    /**
     * Get user name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->response['name'] ?: null;
    }

    /**
     * Get user given name
     *
     * @return string|null
     */
    public function getGivenName()
    {
        return $this->response['given_name'] ?: null;
    }

    /**
     * Get user family name
     *
     * @return string|null
     */
    public function getFamilyName()
    {
        return $this->response['family_name'] ?: null;
    }

    /**
     * Get user email
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->response['email'] ?: null;
    }

    /**
     * Checks if user is verified
     *
     * @return boolean
     */
    public function isVerified()
    {
        return $this->response['verified'] ?: false;
    }

    /**
     * Get user gender
     *
     * @return string|null
     */
    public function getGender()
    {
        return $this->response['gender'] ?: null;
    }

    /**
     * Get user birthdate
     *
     * @return string|null
     */
    public function getBirthdate()
    {
        return $this->response['birthdate'] ?: null;
    }

    /**
     * Get user zoneinfo
     *
     * @return string|null
     */
    public function getZoneinfo()
    {
        return $this->response['zoneinfo'] ?: null;
    }

    /**
     * Get user locale
     *
     * @return string|null
     */
    public function getLocale()
    {
        return $this->response['locale'] ?: null;
    }

    /**
     * Get user phone number
     *
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->response['phone_number'] ?: null;
    }

    /**
     * Get user address
     *
     * @return array
     */
    public function getAddress()
    {
        return $this->response['address'] ?: [];
    }

    /**
     * Checks if user has verified account
     *
     * @return boolean
     */
    public function isVerifiedAccount()
    {
        return $this->response['verified_account'] ?: false;
    }

    /**
     * Get user account type
     *
     * @return string|null
     */
    public function getAccountType()
    {
        return $this->response['account_type'] ?: null;
    }

    /**
     * Get user age range
     *
     * @return string|null
     */
    public function getAgeRange()
    {
        return $this->response['age_range'] ?: null;
    }

    /**
     * Get user payer id
     *
     * @return string|null
     */
    public function getPayerId()
    {
        return $this->response['payer_id'] ?: null;
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
