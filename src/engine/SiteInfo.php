<?php
namespace engine;

class SiteInfo
{
    public $flags;

    public function __construct()
    {
        global $config_flags;
        if (is_object($config_flags))
            $this->flags = $config_flags;
        else
            $this->flags = new \StdClass;
    }

    public function getName()
    {
        if (isset($this->flags->sitename)) 
            return trim($this->flags->sitename);
        else
            return '(no name found)';
    }

    public function getVersion()
    {
        if (isset($this->flags->siteversion)) 
            return trim($this->flags->siteversion);
        else
            return '(no version found)';
    }

    public function getAuthor()
    {
        if (isset($this->flags->siteauthor)) 
            return trim($this->flags->siteauthor);
        else
            return '(no author found)';
    }

    public function getLaunchYear()
    {
        if (isset($this->flags->launchyear)) 
            return trim($this->flags->launchyear);
        else
            return date('Y');
    }

    public function getCopyright()
    {
        if ($this->getLaunchYear() >= date('Y')) {
            $copyright = trim($this->getLaunchYear().' '.$this->getAuthor());
        } else {
            $copyright = trim($this->getLaunchYear().' - '.date('Y').' '.$this->getAuthor());
        }
        return $copyright;
    }
}