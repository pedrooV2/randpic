import React, { useCallback, useContext } from 'react';
import { Link } from 'react-router-dom';
import { FiLogIn } from 'react-icons/fi';

import Header from '../../components/Header';
import AuthContext from '../../contexts/auth';

import login from '../../assets/login.svg';
import './styles.css';

function SignIn() {
    const { signed } = useContext(AuthContext);

    console.log(signed);

    return (
        <>
            <Header></Header>
            <main className="container">
                <div className="imgLogin">
                    <img src={login} alt="Imagem ilustrativa" />
                </div>
                <div className="form">
                    <form action="#">
                        <h2>Sign In</h2>
                        <label htmlFor="email">Email</label>
                        <input type="text" id="email" name="email" />
                        <label htmlFor="password">Password</label>
                        <input type="text" id="password" name="password" />
                        <button type="submit">Sign In <FiLogIn></FiLogIn></button>
                        <p>Don't have an account?<Link to="/register"> Sign up here</Link>
                        </p>
                    </form>
                </div>
            </main>
        </>
    );
}

export default SignIn;
