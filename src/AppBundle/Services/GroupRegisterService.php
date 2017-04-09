<?php

namespace AppBundle\Services;
use Symfony\Component\Ldap\Ldap;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;

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
}