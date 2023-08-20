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
   *
   * @var string
   */
  protected static $defaultName = "login";

  /**
   * The command description shown when running "php bin/demo list".
   *
   * @var string
   */
  protected static $defaultDescription = "Login via username and password";

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

    // $user = (string) $io->ask(sprintf("what is your username?"));
    // $password = (string) $io->askHidden(sprintf("what is your passsword?"));

    // $io->comment("your username is " . $user);
    // $io->comment("your password is " . $password);

    if ($user = "" && ($password = "")) {
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
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer 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",
      ],
      CURLOPT_USERAGENT => "soroush2025",
    ]);

    $response = curl_exec($curl);
    // var_dump($response);
    curl_close($curl);
    $result = json_decode($response, true);
    // var_dump($result);
    foreach ($result as $value) {
      var_dump($value["name"]);
      # code...
    }
    $response;

    // if ($answer === $result) {
    //   $io->success("Well done!");
    // } else {
    //   $io->error(sprintf("Aww, so close. The answer was %s", $result));
    // }

    // if ($io->confirm("Play again?")) {
    //   return $this->execute($input, $output);
    // }

    return Command::SUCCESS;
  }
}
