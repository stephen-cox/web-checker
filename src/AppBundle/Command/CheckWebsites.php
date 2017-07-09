<?php

namespace AppBundle\Command;

use AppBundle\Checkers\Curl;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CheckWebsites extends ContainerAwareCommand {
  
  /**
   * {@inheritdoc}
   */
  protected function configure() {
  
    $this
      ->setName('app:check-websites')
      ->setDescription('Check and update the status of all websites.');
  }
  
  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    
    $output->writeln('Checking websites');
  
    // Get list of websites to check
    $doctrine = $this->getContainer()->get('doctrine');
    $em = $doctrine->getEntityManager();
    $repository = $em->getRepository('AppBundle:Website');
    $websites = $repository->findAll();
    
    // Check each website
    foreach ($websites as $website) {
      
      // Check the website
      $output->writeln('Checking ' . $website->getDisplayName());
      $checker = new Curl($website->getUrl(), $website->getCheckString());
      $checker->check();
      
      // Update status
      $website->setStatus($checker->getError());
  
      // Print the results
      if ($checker->getError() == 0) {
        $output->writeln('<info>OK</info>');
      }
      else {
        $output->writeln('<error>Error: ' . $checker->getErrorMessage() . '</error>');
      }
    }
    
    // Save changes to database
    $em->flush();
  }
  
}