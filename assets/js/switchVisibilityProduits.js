import axios from "axios";

// On récupere tous les inputs switch
let switchs = document.querySelectorAll("[data-switch-active-produits]");

// Si le switch n'est pas valide
if (switchs) {
  switchs.forEach((e) => {
    e.addEventListener("change", () => {
      // On recup l'id du produits dans la valeur de l'input
      let produitId = e.value;

      // On envoie la requete en ajax

      axios.get(`/admin/produits/switch/${produitId}`);
    });
  });
}
