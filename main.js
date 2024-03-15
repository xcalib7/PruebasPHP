
const listContacts= async()=>{

    try {
        const response= await fetch('API.php')
        
        const users = await response.json();
        console.log(users);
        
        let contenido=``

        users.forEach((user, index) => {
            
            contenido+=`
            
            <tr>
                <td>${index+1}</td>
                <td>${user.nombre}</td>
                <td>${user.apellido}</td>
                <td>${user.dni}</td>
            </tr>
            
            `

        });
      

        tablebody_users.innerHTML = contenido;
        
        
    } catch (exc) {
        console.log(exc);
    }


}


window.addEventListener("load", async () => {

await listContacts();

$()

});