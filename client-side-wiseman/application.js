class WiseMan_Blog {
    constructor(code, server = false) {
        this.blogcode = code;
        this.encryption_intensity = 5;
        this.pages = [];
        this.application_state = "CLIENT/SIDE";
        this.application_key = "__APPLICATION_KEY__";

        if (server == false) {
            this.server = 'wiseman.bloging.external.domain.tdl';
        }
    }

    encryption() {
        //This will encrypt and contain all the data at REST. 

        function encryption_algorithm(text, key) {
            let result = '';
            for (let i = 0; i < text.length; i++) {
                const char = text[i];
                let start = 0;

                if (/[a-z]/.test(char)) {
                    start = 'a'.charCodeAt(0);
                } else if (/[A-Z]/.test(char)) {
                    start = 'A'.charCodeAt(0);
                } else if (/[0-9]/.test(char)) {
                    start = '0'.charCodeAt(0);
                } else {
                    result += char;
                    continue;
                }

                const shiftedChar = String.fromCharCode(
                    (char.charCodeAt(0) - start + key) % (/[a-zA-Z]/.test(char) ? 26 : 10) + start
                );
                result += shiftedChar;
            }
            return result;
        }

        function encrypt(text, key = false) {
            if (key == false) {
                key = this.encryption_intensity;
            }
            return this.encryption.encryption_algorithm(text, key);
        }

        function decrypt(text, key = false) {
            if (key == false) {
                key = this.encryption_intensity;
            }
            return this.encryption.encryption_algorithm(text, -key);
        }
    }

    services() {
        const state = this.application_state;
        const wiseman_enc_key = this.application_key;

        async function wiseman_encryption(input) {
            //This Fucntion Will Handle The Data In Transit 
            const end_point = "services/encryption/";
            const blog_code = this.blogcode; // Configure The Bloging Code  

            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', end_point, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = () => {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            resolve(xhr.responseText);
                        } else {
                            reject(`Failed to load Data`);
                        }
                    }
                };
                xhr.send(`input=${encodeURIComponent(input)}&mode=annonymous&action=encode`); // Send Encryption Information To The Server For Encryption
            });

        }

        async function wiseman_decryption(input) {
            //This Function Will Decode The Data In Transit 
            const end_point = "services/encryption/";
            const blog_code = this.blogcode; // Configure The Bloging Code  

            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', end_point, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = () => {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            resolve(xhr.responseText);
                        } else {
                            reject(`Failed to load Data`);
                        }
                    }
                };
                xhr.send(`input=${encodeURIComponent(input)}&mode=annonymous&action=decode`); // Send Encryption Information To The Server For Encryption
            });
        }
    }

    blog() {
        const state = "CLIENT_SIDE";
        async function blog_pages() {
            const end_point = "blog/pages/";
            const blog_code = this.blogcode; // Configure The Bloging Code  

            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', end_point, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = () => {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            resolve(xhr.responseText);
                        } else {
                            reject(`Failed to load block "${blockCode}": ${xhr.status} ${xhr.statusText}`);
                        }
                    }
                };
                xhr.send(`state=${encodeURIComponent(this.state)}&blog_code=${encodeURIComponent(blog_code)}`); // Send form parameter
            });
        }
        //Once All The Pages In The Blog Have Been Identified, 

        async function page(page_id) {
            const end_point = "blog/pages/data/";
            const blog_code = this.blogcode; // Configure The Bloging Code  

            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', end_point, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = () => {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            resolve(xhr.responseText);
                        } else {
                            reject(`Failed to load block "${blockCode}": ${xhr.status} ${xhr.statusText}`);
                        }
                    }
                };
                xhr.send(`page=${encodeURIComponent(page_id)}&state=${encodeURIComponent(this.state)}&blog_code=${encodeURIComponent(blog_code)}`); 
            });
        }

        async function register_blog_pages() {
            let end_point = "blog/pages-register";
        }
    }

    run() {
        //Run The Client Side Application 

        this.pages = this.blog().blog_pages();
        //Retrieve All The Stored Pages

        //pages = this.register_blog_pages(); 
    }
}

let website_engine = new WiseMan_Blog('55223346');

website_engine.blog.register_blog_pages(); 
