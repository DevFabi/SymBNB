<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;



class PasswordUpdate
{

    private $id;

    /**
     * 
     * @Assert\Length(min=8, minMessage="Votre mdp doit faire au moins 8 caractères")
     */
    private $oldPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword", message="Vous n'avez pas correctement confirmé votre nouveau mot de passe")
     */

    private $confirmPassword;

    private $newPassword;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }
}
