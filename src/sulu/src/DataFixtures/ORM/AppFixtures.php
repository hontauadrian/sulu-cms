<?php

declare(strict_types=1);

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Sulu\Bundle\ContactBundle\Entity\Account;
use Sulu\Bundle\ContactBundle\Entity\AccountAddress;
use Sulu\Bundle\ContactBundle\Entity\AccountInterface;
use Sulu\Bundle\ContactBundle\Entity\Address;
use Sulu\Bundle\ContactBundle\Entity\AddressType;
use Sulu\Bundle\ContactBundle\Entity\Contact;
use Sulu\Bundle\ContactBundle\Entity\ContactInterface;
use Sulu\Bundle\WebsiteBundle\Entity\Analytics;

class AppFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder(): int
    {
        return \PHP_INT_MAX;
    }

    public function load(ObjectManager $manager): void
    {

        $this->loadContacts($manager);
        $this->loadAccounts($manager);

        $manager->flush();
    }

    /**
     * @return ContactInterface[]
     */
    private function loadContacts(ObjectManager $manager): array
    {
        return [$this->createContact(
            $manager,
            ['firstName' => 'Hontau', 'lastName' => 'Adrian'],
        )];
    }

    /**
     * @return AccountInterface[]
     */
    private function loadAccounts(ObjectManager $manager): array
    {
        return [$this->createAccount($manager, [
            'name' => 'Plan.Net Technology',
            'corporation' => 'PLAN.NET TECHNOLOGY SRL',
            'email' => 'office@planenet.com',
            'phone' => '+40 356 224 022',
            'address' => [
                'title' => 'Office Timisoara',
                'street' => 'Piata Consiliul Europei United Business Center 3',
                'number' => '2E',
                'zip' => '300088',
                'city' => 'Timisoara',
                'countryCode' => 'RO',
            ],
        ])];
    }

    /**
     * @return Analytics[]
     */
    private function loadAnalytics(ObjectManager $manager): array
    {
        return [$this->createAnalytics(
            $manager,
            [
                'title' => 'Sulu Matomo',
                'type' => 'custom',
                'content' => [
                    'value' => "<script>\n  var _paq = window._paq = window._paq || [];\n  /* tracker methods like \"setCustomDimension\" should be called before \"trackPageView\" */\n  _paq.push(['trackPageView']);\n  _paq.push(['enableLinkTracking']);\n  (function() {\n    var u=\"//sulu.rocks/\";\n    _paq.push(['setTrackerUrl', u+'matomo.php']);\n    _paq.push(['setSiteId', '4']);\n    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];\n    g.async=true; g.src=u+'matomo.php'; s.parentNode.insertBefore(g,s);\n  })();\n</script>",
                    'position' => 'headClose',
                ],
                'allDomains' => true,
            ],
        )];
    }

    private function createContact(ObjectManager $manager, array $data): ContactInterface
    {
        $contact = new Contact();
        $contact->setFirstName($data['firstName']);
        $contact->setLastName($data['lastName']);

        $manager->persist($contact);

        return $contact;
    }

    private function createAccount(ObjectManager $manager, array $data): AccountInterface
    {
        $account = new Account();
        $account->setName($data['name']);
        $account->setCorporation($data['corporation']);
        $account->setMainEmail($data['email']);
        $account->setMainPhone($data['phone']);

        $addressType = $manager->getRepository(AddressType::class)->find(1);
        if (!$addressType) {
            throw new \RuntimeException('AddressType "1" not found. Have you loaded the Sulu fixtures?');
        }

        $address = new Address();
        $address->setTitle($data['address']['title']);
        $address->setStreet($data['address']['street']);
        $address->setNumber($data['address']['number']);
        $address->setZip($data['address']['zip']);
        $address->setCity($data['address']['city']);
        $address->setCountryCode($data['address']['countryCode']);
        $address->setAddressType($addressType);

        $accountAddress = new AccountAddress();
        $accountAddress->setAddress($address);
        $accountAddress->setAccount($account);
        $accountAddress->setMain(true);

        $manager->persist($address);
        $manager->persist($account);
        $manager->persist($accountAddress);

        return $account;
    }

    private function createAnalytics(ObjectManager $manager, array $data): Analytics
    {
        $analytics = new Analytics();
        $analytics->setTitle($data['title']);
        $analytics->setType($data['type']);
        $analytics->setContent($data['content']);
        $analytics->setAllDomains($data['allDomains']);
        $analytics->setWebspaceKey('demo');

        $manager->persist($analytics);

        return $analytics;
    }
}
