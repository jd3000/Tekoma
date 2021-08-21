<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Prospect
{
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=25)
     */
    private $firstname;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=25)
     */
    private $lastname;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $prospectEmail;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=5)
     */
    private $subject;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     */
    private $message;

    /**
     * @var Product|null
     */
    private $product;

    /**
     * @var boolean|null
     */
    private $agreeTerms;

    /**
     * @return null|string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param null|string $firstname
     * @return Prospect
     */
    public function setFirstname(?string $firstname): Prospect
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param null|string $lastname
     * @return Prospect
     */
    public function setLastname(?string $lastname): Prospect
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getProspectEmail(): ?string
    {
        return $this->prospectEmail;
    }

    /**
     * @param null|string $email
     * @return Prospect
     */
    public function setProspectEmail(?string $prospectEmail): Prospect
    {
        $this->prospectEmail = $prospectEmail;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param null|string $subject
     * @return Prospect
     */
    public function setSubject(?string $subject): Prospect
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param null|string $message
     * @return Prospect
     */
    public function setMessage(?string $message): Prospect
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     * @return Prospect
     */
    public function setProduct(?Product $product): Prospect
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get the value of agreeTerms
     *
     * @return  boolean|null
     */
    public function getAgreeTerms()
    {
        return $this->agreeTerms;
    }

    /**
     * Set the value of agreeTerms
     *
     * @param  boolean|null  $agreeTerms
     *
     * @return  self
     */
    public function setAgreeTerms($agreeTerms)
    {
        $this->agreeTerms = $agreeTerms;

        return $this;
    }
}
