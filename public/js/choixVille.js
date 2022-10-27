
window.onload = () => {
    let ville = document.querySelector("#sortie_modifier_ville");

    ville.addEventListener("change", function(){
        let form = this.closest("form");
        let data = this.name + "=" + this.value ;

        console.log(data),


        fetch(window.location.href, {
            method: form.getAttribute("method"),
            mode: "same-origin",
            credentials: "same-origin",
            body: data,
            headers: {
                "content-type": "application/x-www-form-urlencoded; charset:utf-8"
            }
        })
            .then(response => response.text())
            .then(html => {
                let content = document.createElement("html");
                content.innerHTML = html;

                let nouveauSelect = content.querySelector("#sortie_modifier_lieu");
                document.querySelector("#sortie_modifier_lieu").replaceWith(nouveauSelect);

                console.log(nouveauSelect);
            })

            .catch(error =>{
                console.log(error);
            })

    })
}