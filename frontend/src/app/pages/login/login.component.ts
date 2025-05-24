import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { NzInputModule } from 'ng-zorro-antd/input';
import { NzButtonModule } from 'ng-zorro-antd/button';
import { NzIconModule } from 'ng-zorro-antd/icon';
import { AuthService } from '../../auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule, NzInputModule, NzButtonModule, NzIconModule],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {
  email = '';
  password = ''; // Alterado de 'senha' para 'password'
  isLoading = false; // Indicador de carregamento

  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  onSubmit(): void {
    // Validação dos campos obrigatórios
    if (!this.email || !this.password) {
      alert('Por favor, preencha todos os campos obrigatórios: Email e Senha.');
      return;
    }

    // Validação do formato do email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(this.email)) {
      alert('Por favor, insira um e-mail válido.');
      return;
    }

    this.isLoading = true;

    const credentials = {
      email: this.email,
      password: this.password
    };

    // Depuração: Logar as credenciais
    console.log('Enviando credenciais:', credentials);

    this.authService.login(credentials).subscribe({
      next: (response) => {
        console.log('Login bem-sucedido:', response);
        this.authService.storeToken(response.access_token);
        alert('Login realizado com sucesso!');
        this.router.navigate(['/catalogo']); // Redireciona para a página de catálogo
      },
      error: (error) => {
        console.error('Erro no login:', error);
        this.isLoading = false;
        if (error.status === 422 && error.error && error.error.errors) {
          let errorMessages = Object.values(error.error.errors).flat().join('\n');
          alert('Erro de validação:\n' + errorMessages);
        } else if (error.status === 401) {
          alert('Credenciais inválidas. Verifique seu email e senha.');
        } else {
          const backendMessage = error.error && error.error.message ? error.error.message : 'Ocorreu um erro desconhecido.';
          alert('Erro ao fazer login:\n' + backendMessage);
        }
      }
    });
  }

  onForgotPassword(): void {
    alert('Vamos te ajudar a recuperar sua senha!');
    // Aqui você pode redirecionar ou abrir um modal
  }

  loginWithGoogle(): void {
    alert('Futuramente, aqui vai entrar a integração com o Google!');
    // Aqui vai a lógica de autenticação com Firebase ou OAuth no futuro
  }
}
