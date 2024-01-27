<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AccountRepository;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account extends User
{
   
    #[ORM\Column(length: 255)]
    private $phone;


    #[ORM\Column(length: 255)]
    private ?string $title ;

    #[ORM\Column(length: 255)]
    private ?string $address ;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Notification::class)]
    private Collection $notifications;

    // #[ORM\OneToMany(mappedBy: 'account', targetEntity: Followers::class)]
    // #[ORM\JoinColumn(nullable:false)]
    // private Collection $followers;
   
    #[ORM\Column(type:"string",length: 255, nullable: true)]
    private  $avatar ;

   

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        // $this->followers = new ArrayCollection();
    }

    
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    // Other methods inherited from the User class and additional methods can be added here

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAccount($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAccount() === $this) {
                $post->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setOwner($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            if ($notification->getOwner() === $this) {
                $notification->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Followers>
     */
    // public function getFollowers(): Collection
    // {
    //     return $this->followers;
    // }

    // public function addFollower(Followers $follower): static
    // {
        

    //     return $this;
    // }

    // public function removeFollower(Followers $follower): static
    // {
       

    //     return $this;
    // }

    public  function  getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
    public  function  getAvatarPath() :string
    {
        return 'uploads/avatars/' . $this->getAvatar() ?? 'default.webp';
    }
    
}
