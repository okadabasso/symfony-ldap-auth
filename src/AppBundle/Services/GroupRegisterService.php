<?php

namespace AppBundle\Services;
use Symfony\Component\Ldap\Ldap;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\Ldap\Entry;
    
/**
 * @Service("app.group_register_service")
 */
 class GroupRegisterService {
    /**
     * 
     * @var Ldap
     */
    private $ldap;

    /**
     * @var array 
     */
    private $options;
    
    
    /**
     * GroupRegisterService constructor
     *
     * @InjectParams({
     *     "ldap" = @Inject("ldap"),
     *     "options" = @Inject("%ldap%"),
     * })
     * 
     * @param Ldap $ldap
     * @param array $options
     */
     public function __construct(Ldap $ldap, $options){
		$this->ldap = $ldap;
		$this->options = $options;
	}
	
	public function getOptions(){
		return $this->options;
	}
	public function ListGroups(){
		$this->ldap->bind($this->options["search_dn"], $this->options["search_password"]);
		$query = $this->ldap->query(
				"ou=groups,dc=example,dc=com",
				"cn=*",
				["filter" =>["cn"]]);
		$groupEntries = $query->execute()->toArray();
		return $groupEntries;
	}
 	public function getGroup($cn){
        $this->ldap->bind($this->options["search_dn"], $this->options["search_password"]);
        $query = $this->ldap->query(
            "ou=groups,dc=example,dc=com",
            "cn={$cn}",
            ["filter" =>["cn","ou"]]);
        $groupEntries = $query->execute()->toArray();
        if($groupEntries){
            return $groupEntries[0];
        }
        return null;
	}
  	public function update($data){
        $this->ldap->bind("uid=administrator,ou=users,dc=example,dc=com", "administrator");
        $entryManager = $this->ldap->getEntryManager();
        
        $dn = "cn={$data["cn"]},ou=groups,dc=example,dc=com";
        $entry = new Entry($dn, [
            "cn" => [$data["cn"]],
            "ou" => [$data["ou"]],
            "member"=> ["uid=testuser01,ou=users,dc=example,dc=com"]
        ]);
        $entryManager->update($entry);
        
	}
 }