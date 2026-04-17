const readline = require("readline");

const BASE_URL = "http://localhost:8000/service.php";

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

function ask(q) {
  return new Promise(res => rl.question(q, res));
}

async function main() {
  while (true) {
    console.log("\n1. Add Pokemon\n2. Get Pokemon\n3. Exit");
    const choice = await ask("Choose: ");

    if (choice === "1") {
      const name = await ask("Name: ");
      const type = await ask("Type: ");
      const level = await ask("Level: ");

      const res = await fetch(`${BASE_URL}?action=add`, {
        method: "POST",
        body: JSON.stringify({ name, type, level })
      });

      console.log(await res.json());
    }

    else if (choice === "2") {
      const id = await ask("Pokemon ID: ");
      const res = await fetch(`${BASE_URL}?action=get&id=${id}`);
      console.log(await res.json());
    }

    else break;
  }

  rl.close();
}

main();