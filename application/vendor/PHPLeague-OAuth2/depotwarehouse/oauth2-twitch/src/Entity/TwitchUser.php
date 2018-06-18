<?php namespace Depotwarehouse\OAuth2\Client\Twitch\Entity;

/**
 * Class TwitchUser
 * @package Depotwarehouse\OAuth2\Client\Twitch\Entity
 */
class TwitchUser
{
    /**
     * @var string
     */
    protected $username;
    /**
     * @var  string
     */
    protected $display_name;
    /**
     * @var  int
     */
    protected $id;
    /**
     * @var  string
     */
    protected $type;
    /**
     * @var  string
     */
    protected $bio;
    /**
     * @var  string
     */
    protected $email;
    /**
     * @var  bool
     */
    protected $partnered;
    /**
     * @var string
     */
    protected $logo;

    /**
     * TwitchUser constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        $this->id = $attributes['_id'];
        $this->display_name = $attributes['display_name'];
        $this->type = $attributes['type'];
        $this->bio = $attributes['bio'];
        $this->email = $attributes['email'];
        $this->partnered = $attributes['partnered'];
        $this->username = $attributes['name'];
        $this->logo = $attributes['logo'];
    }

    /**
     * Get the contents of the user as a key-value array.
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->display_name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return boolean
     */
    public function isPartnered()
    {
        return $this->partnered;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Will return a url to the profile image for the user's account.
     * If the user has not setup a profile image an empty string
     * is returned from the Twitch API
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }
}
