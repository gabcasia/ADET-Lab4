const readline = require("readline");

const BASE_URL = "http://localhost:8000/service.php";

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

function ask(question) {
  return new Promise(resolve => rl.question(question, resolve));
}

async function register() {
  const username = await ask("Username: ");
  const password = await ask("Password: ");

  const res = await fetch(`${BASE_URL}?action=register`, {
    method: "POST",
    body: JSON.stringify({ username, password }),
  });

  console.log(await res.json());
}

async function login() {
  const username = await ask("Username: ");
  const password = await ask("Password: ");

  const res = await fetch(`${BASE_URL}?action=login`, {
    method: "POST",
    body: JSON.stringify({ username, password }),
  });

  console.log(await res.json());
}

async function main() {
  while (true) {
    console.log("\n1. Register\n2. Login\n3. Exit");
    const choice = await ask("Choose: ");

    if (choice === "1") await register();
    else if (choice === "2") await login();
    else break;
  }
  rl.close();
}

main();