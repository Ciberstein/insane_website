var gift;
var clicked = 0;

getRouletteItems = async function () {
  const url = `process?to=request&target=roulette`;

  var items = [];

  await axios
    .get(url)
    .then((res) => (items = res.data.data))
    .catch((err) => console.error("debugger", err));
  return items.map((item) => item.vnum);
};

$(document).ready(async function () {
  var present = await getRouletteItems();

  sendItem = async function (characterId, vnum) {
    const url = `process?to=roulette`;

    await axios
      .post(url, { characterId, vnum })
      .then((res) => console.log(res.data))
      .catch((err) => console.error("debugger", err));
  };

  getCharacters = async function () {
    const url = `process?to=request&target=characters`;

    var characters = [];

    await axios
      .get(url)
      .then((res) => (characters = res.data.data))
      .catch((err) => console.error("debugger", err));

    return characters;
  };

  characterPopup = async function () {
    const characters = await getCharacters();

    const characterOptions = characters.reduce((obj, character) => {
      obj[character.CharacterId] = character.Name;
      return obj;
    }, {});

    const { value: character } = await Swal.fire({
      title: "Select your character",
      input: "select",
      inputOptions: characterOptions,
      inputPlaceholder: "Choose any option",
      showCancelButton: true,
      inputValidator: (value) => {
        return new Promise((resolve) => {
          if (value) {
            resolve();
          } else {
            resolve("You need to select some character");
          }
        });
      },
    });
    if (character) {
      iniGame(character);
    }
  };

  iniGame = async function (characterId) {
    gift = Math.floor(Math.random() * 6);

    const url = `process?to=roulette`;

    await axios
      .post(url, { characterId, vnum: present[gift] })
      .then((res) => {
        clicked++;
        // $(".board_start").html('<img src="images/roulette_board_go.png">');
        TweenLite.killTweensOf($(".board_on"));
        TweenLite.to($(".board_on"), 0, {
          css: {
            rotation: rotationPos[gift],
          },
        });
        TweenLite.from($(".board_on"), 5, {
          css: {
            rotation: -3000,
          },
          onComplete: endGame,
          ease: Sine.easeOut,
        });
        //console.log(`Prize: ${present[gift]}`);
      })
      .catch((err) => {
        console.error("Error:", err.response.data.error);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: err.response.data.error,
        });
      });
  };

  var rotationPos = new Array(60, 120, 180, 240, 300, 360);

  TweenLite.to($(".board_on"), 60, {
    css: {
      rotation: -4000,
    },
    ease: Linear.easeNone,
  });

  function endGame() {
    setTimeout(function () {
      Swal.fire({
        imageUrl: `http://www.westnos.it/images/items/${present[gift]}.png`,
        imageWidth: 40,
        imageHeight: 40,
        title: "Congrats!",
        text: "The item has been sent to your inventory",
        backdrop: `
          rgba(0,0,123,0.4)
          url("https://media.tenor.com/v35v-zbtwnUAAAAi/confetti.gif")
          center center / cover
          no-repeat
        `,
      }).then(() => location.reload());
    }, 1000);
  }

  $(".popup .btn").on("click", function () {
    location.reload();
  });
});

$(async function () {
  var present = await getRouletteItems();

  for (var i = 0; i < present.length; i++) {
    $(".board_on").append(
      `<img src="http://www.westnos.it/images/items/${present[i]}.png" />`
    );
  }

  $(".join").on("mousedown", function () {
    if (clicked <= 0) {
      characterPopup();

      //iniGame(Math.floor(Math.random() * 6));
    } else if (clicked >= 1) {
      event.preventDefault();
      alert("You have already participated in the event.");
    }
  });
});
