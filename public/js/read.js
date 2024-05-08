
const like = document.getElementById('heart');
var newImg = document.createElement('img');
newImg.src = "/pictures/heart2.png";
newImg.className = "like";
like.appendChild(newImg);



/*

for(let i=1; i<17;i++){
    var newImg = document.createElement('img');
    newImg.src = `./src/assets/img/${i}.jpg`;
    newImg.title = titles[i-1];
    newImg.className="little";
    page.appendChild(newImg);
    
} 
var images = document.getElementsByClassName('little');
 
for(let i=0; i<images.length;i++){
    images[i].addEventListener('click', ()=>{
        var blocks = document.createElement('blocks');
        blocks.className="blocks";
        var BigImg=document.createElement('img');
        BigImg.className="Big";
        var arrowRightImg=document.createElement('img');
        arrowRightImg.className="arrowRightImg";

        var arrowLeftImg=document.createElement('img');
        arrowLeftImg.className="arrowLeftImg";

        var crossImg=document.createElement('img');
        crossImg.className="crossImg";

        crossImg.src = `./src/assets/nav/cross.png`;
        BigImg.src = `./src/assets/img/${i+1}.jpg`;
        page.appendChild(blocks); 
        arrowRightImg.src = `./src/assets/nav/ArrowRight.png`;
        arrowLeftImg.src = `./src/assets/nav/ArrowLeft.png`;
        blocks.appendChild(arrowRightImg); 
        blocks.appendChild(arrowLeftImg);
        blocks.appendChild(BigImg);
        blocks.appendChild(crossImg);
        var a=i;
        arrowRightImg.addEventListener('click', ()=>{
            i=i+1;
            if (i>=images.length) i=0;
            BigImg.src = `./src/assets/img/${i+1}.jpg`;    
        }); 

        arrowLeftImg.addEventListener('click', ()=>{
            i=i-1;
            if (i<0) i=images.length-1;
            BigImg.src = `./src/assets/img/${i+1}.jpg`;    
        }); 


         blocks.appendChild(BigImg);
        document.querySelector('.crossImg').addEventListener('click', ()=>{
            blocks.remove();
            i=a;
        });
        
    });
}


//console.log(location.href);
//let h=0;
//localStorage.photo="Photo";
//console.log(localStorage.getItem('photo'));
//if(location.pathname=='/photo')
//console.log(location.pathname);
//h++;
// console.log(h);
//localStorage.setItem("/photo", 1);
localStorage.setItem("/photo",+localStorage.getItem("/photo")+1);
sessionStorage.setItem("/photo",+sessionStorage.getItem("/photo")+1);

 
const allCookies = document.cookie
    .split(';')
    .reduce((obj, x) => {
        const [name, value] = x.trim().split('=');

        return {
            ...obj,
            [name]: value,
        };
    });
document.cookie = `photo=${parseInt(allCookies.photo)+1}`;

 


    
  */






