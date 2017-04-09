<?php

namespace AppBundle\Services;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Ldap\Ldap;
use AppBundle\Models\User;

class UserProvider  implements UserProviderInterface{
    /**
     * 
     * @var Ldap
     */
    private $ldap;
    /**
     * 
     * @var array
     */
    private $options;
    
    public function __construct(Ldap $ldap, array $options){
        $this->ldap = $ldap;
        $this->options = $options;
    }
    public function loadUserByUsername($username)
    {
        
        $userEntry = $this->findUserEntry($username);
        $uid = $userEntry->getAttribute("uid")[0];
        if (!$uid) {
        	throw new UsernameNotFoundException(
            	sprintf('Username "%s" does not exist.', $username)
            );
        }
        $userDn = $userEntry->getDn();
        $commonName = $userEntry->getAttribute("cn")[0];
		$groupEntries = $this->findGroupEntries($userDn);
		$groups = [];
        foreach ($groupEntries as $groupEntry){
        	$groups[] = $groupEntry->getAttribute("cn")[0];
        }
		
		
        return new User($uid, null, "", $commonName, $groups);

    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
                );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'AppBundle\Models\User';
    }
    /**
     * 
     * @param unknown $username
     * @throws UsernameNotFoundException
     * @return Ldap
     */
    private function findUserEntry($username){
    	$this->ldap->bind($this->options["search_dn"], $this->options["search_password"]);
    	$query = $this->ldap->query(
    			$this->options["users"]["base_dn"],
    			str_replace("{username}", $username,$this->options["users"]["filter"]),
    			["filter" =>["dn","uid","userpassword","cn"]]);
    	$userEntries = $query->execute()->toArray();
    	if(!$userEntries){
    		throw new UsernameNotFoundException(
    				sprintf('Username "%s" does not exist.', $username)
    				);
    	}
    	$entry = $userEntries[0];
    	 return $entry;
    }
    private function findGroupEntries($userDn){
    	$this->ldap->bind($this->options["search_dn"], $this->options["search_password"]);
    	$query = $this->ldap->query(
    			"ou=groups,dc=example,dc=com",
    			str_replace("{userDn}", $userDn, "member={userDn}"),
    			["filter" =>["cn"]]);
    	$groupEntries = $query->execute()->toArray();
    	if(!$groupEntries){
    		return [];
    	}
    	
    	return $groupEntries;
    }    
}