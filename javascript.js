
document.addEventListener('DOMContentLoaded', function () {
    var secondDiv = document.getElementById('bg');

    // Listen for scroll events
    window.addEventListener('scroll', function () {
        // Check if the user has scrolled down a certain amount (adjust as needed)
        if (window.scrollY > 250) {
            // Slide down the sticky div by changing the 'top' property
            secondDiv.style.top = '-1%';
        } else {
            // Move the sticky div back above the viewport when scrolling up
            secondDiv.style.top = '-100px';
        }
    });
});





function toggleDropdown() {
    const subMenu = document.querySelector('.sub-menu1');
    subMenu.style.display = (subMenu.style.display === 'none' || subMenu.style.display === '') ? 'block' : 'none';
  }

        let isMenuOpen = false;
        
        const toggleMenu = () => {
            const menu = document.getElementById("ul");
            
            if (!isMenuOpen) {
                menu.style.height = "auto";
                isMenuOpen = true;
            } else {
                menu.style.height = "0px";
                isMenuOpen = false;
            }
        };
        
        const closeMenu = () => {
            const menu = document.getElementById("ul");
            menu.style.height = "0px";
            isMenuOpen = false;
        };




        const imageUrls = [
            'images/airport1.jpg',
            'images/picc2.jpg',
            'images/picc4.jpg',
            'images/picc5.jpg',
            'images/picc6.jpg',
            'images/picc8.jpg',
            'images/picc9.jpg',
            'images/picc10.jpg',
            'images/picc11.jpg',
            'images/picc14.jpg',
            'images/picc15.jpg',
            'images/picc16.jpg',
            'images/picc17.jpg',
            'images/picc18.jpg',
            'images/picc19.jpg',
            'images/picc20.jpg',
            'images/picc22.jpg',
            'images/picc23.jpg',
            'images/picc24.jpg',
            'images/picc25.jpg',
            'images/picc26.jpg',
            'images/picc27.jpg',
            'images/picc28.jpg',
            'images/picc29.jpg',
            'images/picc30.jpg',
            'images/picc31.jpg',
            'images/picc32.jpg',
            'images/picc33.jpg',
            'images/picc34.jpg',
        
        ];
        
        let currentIndex = 0;
        
        function updateImage() {
            const rotatingImage = document.getElementById('rotating-image');
            rotatingImage.src = imageUrls[currentIndex];
        }
        
        function goToPrevious() {
            currentIndex = currentIndex === 0 ? imageUrls.length - 1 : currentIndex - 1;
            updateImage();
        }
        
        function goToNext() {
            currentIndex = (currentIndex + 1) % imageUrls.length;
            updateImage();
        }
        
        // Automatically rotate images every 3 seconds
        setInterval(goToNext, 3000);
        
        // Initial image update
        updateImage();
        
        

    var open = false;
        function open1(){
            open = true; 
            terminate();
            terminate2();
                document.getElementById('div1').style.display='block'; 
                document.getElementById('link1').style.backgroundColor='white';
                document.getElementById('link1').style.color='#0e0e88';
            }
            function open2(){
                open = true; 
                terminate();
                terminate2();
                    document.getElementById('div2').style.display='block'; 
                    document.getElementById('link2').style.backgroundColor='white';
                    document.getElementById('link2').style.color='#0e0e88';
                }
                function open3(){
                    open = true; 
                    terminate();
                    terminate2();
                        document.getElementById('div3').style.display='block'; 
                        document.getElementById('link3').style.backgroundColor='white';
                        document.getElementById('link3').style.color='#0e0e88';
                    }    function open4(){
                        terminate();
                        terminate2();
                        open = true; 
                            document.getElementById('div4').style.display='block'; 
                            document.getElementById('link4').style.backgroundColor='white';
                            document.getElementById('link4').style.color='#0e0e88';
                        }    function open5(){
                            terminate();
                            terminate2();
                            open = true; 
                                document.getElementById('div5').style.display='block'; 
                                document.getElementById('link5').style.backgroundColor='white';
                                document.getElementById('link5').style.color='#0e0e88';
                            }    function open6(){
                                terminate();
                                terminate2();
                                open = true; 
                                    document.getElementById('div6').style.display='block'; 
                                    document.getElementById('link6').style.backgroundColor='white'; 
                                    document.getElementById('link6').style.color='#0e0e88';
                                }
         


                                

                                
            function terminate(){
                document.getElementById('div1').style.display='none';
                document.getElementById('div2').style.display='none'; 
                document.getElementById('div3').style.display='none'; 
                document.getElementById('div4').style.display='none'; 
                document.getElementById('div5').style.display='none'; 
                document.getElementById('div6').style.display='none';
            }
            function terminate2(){
                document.getElementById('link1').style.backgroundColor='#0e0e88'; 
               document.getElementById('link1').style.color='white';

               document.getElementById('link2').style.backgroundColor='#0e0e88'; 
               document.getElementById('link2').style.color='white';

               document.getElementById('link3').style.backgroundColor='#0e0e88'; 
               document.getElementById('link3').style.color='white';

               document.getElementById('link4').style.backgroundColor='#0e0e88'; 
               document.getElementById('link4').style.color='white';

               document.getElementById('link5').style.backgroundColor='#0e0e88'; 
               document.getElementById('link5').style.color='white';

               document.getElementById('link6').style.backgroundColor='#0e0e88'; 
               document.getElementById('link6').style.color='white';
            }


            var eligible = false;
            function drop(){
               

                if(eligible === false){
                    document.getElementById('open').style.height='auto';
                    document.getElementById('direct').innerHTML=`<i class="fa fa-toggle-down"></i>`;
                    eligible=true; 
                }else{
                    document.getElementById('open').style.height='0px';
                    document.getElementById('direct').innerHTML=`<i class="fa fa-caret-square-o-right"></i>`;
                    eligible=false;
                }
            }