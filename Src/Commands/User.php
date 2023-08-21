<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class User extends Command
{
  /**
   * The name of the command (the part after "bin/demo").
   * @var string
   */
  protected static $defaultName = "login";
  /**
   * The command description shown when running "php bin/demo list".
   * @var string
   */
  protected static $defaultDescription = "Login via username and password";
  protected $dbConnection;
  function __construct($dbConnection)
  {
    parent::__construct();
    $this->dbConnection = $dbConnection;
    // var_dump($dbConnection->get_server_info());
  }

  /**
   * Execute the command
   *
   * @param  InputInterface  $input
   * @param  OutputInterface $output
   * @return int 0 if everything went fine, or an exit code.
   */
  protected function execute(
    InputInterface $input,
    OutputInterface $output
  ): int {
    $user = null;
    $password = null;

    $io = new SymfonyStyle($input, $output);

    $user = (string) $io->ask(
      sprintf(
        "what is your GitHub username Or just Enter To use Dafault username?"
      )
    );
    $password = (string) $io->askHidden(
      sprintf(
        "what is your Github passsword Or just Enter To use Dafault password?"
      )
    );
    $token = (string) $io->ask(
      sprintf("what is your Github Token Or just Enter To use Dafault Token?")
    );

    $io->comment("your username is " . $user);
    // $io->comment("your password is " . $password);

    $u = $user ?: "soroush2025";
    $p = $password ?: "12345";
    $t = $token ?: "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";

    $query =
      "INSERT INTO `token`(`username`, `password`, `token`)
    VALUES ('" .
      $u .
      "', '" .
      $p .
      "', '" .
      $t .
      "')";

    $result = $this->dbConnection->query($query);
    if (!$result) {
      echo "Error description: " . $this->dbConnection->error;
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.github.com/user/repos",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => ["Authorization: Bearer " . $t],
      CURLOPT_USERAGENT => $u,
    ]);

    $response = curl_exec($curl);
    // // var_dump($response);
    curl_close($curl);
    $result = json_decode($response, true);
    // // var_dump($result);

    if (!is_null($result)) {
      echo "your Repository List";
      $i = 0;
      foreach ($result as $value) {
        echo ++$i;
        var_dump($value["name"]);
      }
    }

    if ($io->confirm(sprintf("do you want to Create New repository?"))) {
      $repositoryName = (string) $io->ask("what is the name?");
      $name = $repositoryName ?: "sample1";
      $this->createRep($u, $t, $repositoryName);
    }

    if ($io->confirm(sprintf("do you want to Delete Any repository?"))) {
      $repositoryName = (string) $io->ask("what is the name?");
      $name = $repositoryName ?: "sample1";
      $this->deleteRep($u, $t, $repositoryName);
    }

    // // if ($answer === $result) {
    // //   $io->success("Well done!");
    // // } else {
    // //   $io->error(sprintf("Aww, so close. The answer was %s", $result));
    // // }

    if ($io->confirm("Do again?")) {
      return $this->execute($input, $output);
    }
    return Command::SUCCESS;
  }

  protected function createRep($u, $t, $name)
  {
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.github.com/user/repos",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>
        '{
    "name": "' .
        $name .
        '",
    "private": false,
    "is_template": true
}',
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $t,
        "Content-Type: application/json",
      ],
      CURLOPT_USERAGENT => $u,
    ]);

    $response = curl_exec($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    if ($httpcode == 201) {
      echo "repository created succefuly";
    } else {
      echo "the error code is " . $httpcode;
    }
    return $httpcode;
  }
  protected function deleteRep($u, $t, $name)
  {
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.github.com/repos/" . $u . "/" . $name,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "DELETE",
      CURLOPT_HTTPHEADER => ["Authorization: Bearer " . $t],
      CURLOPT_USERAGENT => $u,
    ]);

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($httpcode == 204) {
      echo "repository deleted succefuly";
    } else {
      echo "the error code is " . $httpcode;
    }
    return $httpcode;
  }
}
