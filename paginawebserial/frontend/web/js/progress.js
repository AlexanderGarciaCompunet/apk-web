const prevBtns = document.querySelectorAll(".btn-prev");
const nextBtns = document.querySelectorAll(".btn-next");
const progress = document.getElementById("progress");
const formSteps = document.querySelectorAll(".step-forms");
const progressSteps = document.querySelectorAll(".progress-step");

let formStepsNum = 0;

nextBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    formStepsNum++;
    updateFormSteps();
    updateProgressbar();
  });
});

prevBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    formStepsNum--;
    updateFormSteps();
    updateProgressbar();
  });
});

// Holaaaaa esto es nuevo
function showCarousel() {
  console.log("HOlaaaaaaaaaaaaaa");
}
//aqui finaliza lo nuevo

function updateFormSteps() {
  formSteps.forEach((formStep) => {
    formStep.classList.contains("step-forms-active") &&
      formStep.classList.remove("step-forms-active");
  });

  formSteps[formStepsNum].classList.add("step-forms-active");
}

function updateProgressbar() {
  progressSteps.forEach((progressStep, idx) => {
    if (idx < formStepsNum + 1) {
      progressStep.classList.add("progress-step-active");
    } else {
      progressStep.classList.remove("progress-step-active");
    }
  });
  progressSteps.forEach((progressStep, idx) => {
    if (idx < formStepsNum) {
      progressStep.classList.add("progress-step-check");
    } else {
      progressStep.classList.remove("progress-step-check");
    }
  });
  const progressActive = document.querySelectorAll(".progress-step-active");
  progress.style.width =
    ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%";
}


document.getElementById('test').addEventListener('change', function () {
  var style = this.value == 1 ? 'block' : 'none';
  document.getElementById('hidden_div').style.display = style;
});



function renderImage() {
  var selected = document.getElementById("selectOption");
  var imgUrl = "";
console.log(selected.value)
  switch (selected.value) {
    case "1":
      imgUrl = "../img/type2.png";
      break;
    case "2":
      imgUrl = "../img/type3.png";
      break;
    case "3":
      imgUrl = "../img/type4.png";
      break;
    case "4":
      imgUrl = "../img/type6.png";
      break;
  }
  document.getElementById("myImg").src = imgUrl;
}


function hiddenDiv(ele) {
  var srcElement = document.getElementById(ele);
  document.getElementById("serialrules-epsilon").disabled = true;
  document.getElementById("serialrules-qtcolumns").disabled = true;
  document.getElementById("serialrules-epsilon").value = null;
  document.getElementById("serialrules-qtcolumns").value = null;
  if (srcElement.style.display == "block") {
    srcElement.style.display = 'none';
  }
}


function showHideDiv(ele) {

  var srcElement = document.getElementById(ele);
  if (srcElement != null) {
    if (srcElement.style.display == "block") {
      srcElement.style.display = 'none';

    } else {
      srcElement.style.display = 'block';
      document.getElementById("serialrules-epsilon").disabled = false;
      document.getElementById("serialrules-qtcolumns").disabled = false;

    }
    return false;
  }
}
