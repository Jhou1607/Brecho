import { Component, OnInit, OnDestroy, ChangeDetectorRef } from '@angular/core';
import { CommonModule, DatePipe } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Subscription } from 'rxjs';
import { Router } from '@angular/router';
import { AuthService, User, UserProfileUpdateData } from '../../auth.service';
import { UsuarioService } from '../../services/usuario.service';
import { environment } from '../../../environments/environment';
import { HeaderComponent } from '../../shared/components/header/header.component';
import { FooterComponent } from '../../shared/components/footer/footer.component';

@Component({
  selector: 'app-perfil',
  standalone: true,
  imports: [CommonModule, FormsModule, HeaderComponent, FooterComponent, DatePipe],
  templateUrl: './perfil.component.html',
  styleUrls: ['./perfil.component.scss']
})
export class PerfilComponent implements OnInit, OnDestroy {
  user: User | null = null;
  editableUser: UserProfileUpdateData = {};
  isEditing = false;
  isLoading = true;
  isSaving = false;
  error: string | null = null;
  successMessage: string | null = null;
  private userSubscription?: Subscription;
  imageBaseUrl = environment.imageBaseUrl;
  private notificationTimer: any;

  constructor(
    public authService: AuthService, // ALTERADO DE private PARA public
    private usuarioService: UsuarioService,
    private router: Router,
    private cdr: ChangeDetectorRef
  ) {}

  private setNotification(message: string, type: 'success' | 'error') {
    clearTimeout(this.notificationTimer);
    if (type === 'success') {
      this.successMessage = message;
      this.error = null;
    } else {
      this.error = message;
      this.successMessage = null;
    }
    this.cdr.detectChanges();
    this.notificationTimer = setTimeout(() => {
      this.successMessage = null;
      this.error = null;
      this.cdr.detectChanges();
    }, 5000);
  }

  ngOnInit(): void {
    this.userSubscription = this.authService.currentUser$.subscribe(currentUser => {
      if (currentUser) {
        this.user = { ...currentUser };
        // A lógica de prefixar imageBaseUrl deve ser feita ao exibir, ou garantir que a API sempre retorne URLs completas.
        // No AuthController@me, já usamos Storage::url(), então a foto_url deve vir completa.
        // if (this.user.foto_url && !this.user.foto_url.startsWith('http') && !this.user.foto_url.startsWith('assets/')) {
        //   this.user.foto_url = this.imageBaseUrl + this.user.foto_url;
        // }
        this.isLoading = false;
        this.error = null;
      } else if (!this.authService.getToken() && !this.isLoading) {
        this.router.navigate(['/login']);
      }
    });

    if (!this.user && this.authService.getToken()) {
      this.isLoading = true;
      this.authService.fetchCurrentUser().subscribe({
        next: (fetchedUser) => {
          this.isLoading = false;
        },
        error: () => {
          this.isLoading = false;
        }
      });
    } else if (!this.authService.getToken()) {
      this.isLoading = false;
      this.router.navigate(['/login']);
    }
  }

  ngOnDestroy(): void {
    this.userSubscription?.unsubscribe();
    clearTimeout(this.notificationTimer);
  }

  handleImageError(): void {
    if (this.user) {
      this.user.foto_url = 'assets/default-user-avatar.svg';
    }
  }

  startEdit(): void {
    if (this.user) {
      this.editableUser = {
        nome_usuario: this.user.nome_usuario,
        email: this.user.email,
        data_nascimento: this.user.data_nascimento ? new Date(this.user.data_nascimento).toISOString().substring(0,10) : undefined,
        sexo: this.user.sexo,
        bio: this.user.bio || ''
      };
      this.isEditing = true;
      this.successMessage = null;
      this.error = null;
    }
  }

  cancelEdit(): void {
    this.isEditing = false;
    this.editableUser = {};
    this.error = null;
    this.successMessage = null;
  }

  saveProfile(): void {
    if (!this.editableUser || !this.user) return;
    this.isSaving = true;
    this.error = null;
    this.successMessage = null;

    const dataToUpdate: UserProfileUpdateData = {};
    let hasChanges = false;

    if (this.editableUser.nome_usuario && this.editableUser.nome_usuario !== this.user.nome_usuario) {
      dataToUpdate.nome_usuario = this.editableUser.nome_usuario;
      hasChanges = true;
    }
    if (this.editableUser.email && this.editableUser.email !== this.user.email) {
      dataToUpdate.email = this.editableUser.email;
      hasChanges = true;
    }
    const currentUserBirthDate = this.user.data_nascimento ? new Date(this.user.data_nascimento).toISOString().substring(0,10) : undefined;
    if (this.editableUser.data_nascimento && this.editableUser.data_nascimento !== currentUserBirthDate) {
      dataToUpdate.data_nascimento = this.editableUser.data_nascimento;
      hasChanges = true;
    }
    if (this.editableUser.sexo && this.editableUser.sexo !== this.user.sexo) {
      dataToUpdate.sexo = this.editableUser.sexo;
      hasChanges = true;
    }
    const currentUserBio = this.user.bio || '';
    // Permite limpar a bio enviando string vazia (que o backend converterá para null se apropriado)
    if (this.editableUser.bio !== undefined && this.editableUser.bio !== currentUserBio) {
      dataToUpdate.bio = this.editableUser.bio;
      hasChanges = true;
    }


    if (!hasChanges) {
      this.isEditing = false;
      this.isSaving = false;
      this.setNotification("Nenhuma alteração detectada.", 'success');
      return;
    }

    this.authService.updateUserProfile(dataToUpdate).subscribe({
      next: (updatedUser) => {
        this.isEditing = false;
        this.isSaving = false;
        this.setNotification('Perfil atualizado com sucesso!', 'success');
      },
      error: (err) => {
        this.setNotification(err.message || 'Falha ao atualizar o perfil.', 'error');
        this.isSaving = false;
      }
    });
  }

  triggerFileInput(): void {
    this.error = null;
    this.successMessage = null;
    this.usuarioService.uploadFotoUsuario().subscribe({
      next: (response) => {
        if (response && response.user) {
          this.authService.updateUser(response.user);
          this.setNotification(response.message, 'success');
        }
      },
      error: (err) => {
        this.setNotification(err.message || 'Erro ao atualizar a foto.', 'error');
      }
    });
  }

  retryFetchUser(): void {
    this.isLoading = true;
    this.error = null;
    this.successMessage = null;
    this.authService.fetchCurrentUser().subscribe({
      next: () => {
        this.isLoading = false; // User será atualizado pelo BehaviorSubject
      },
      error: (err) => {
        this.isLoading = false;
        this.setNotification('Falha ao recarregar o perfil.', 'error');
      }
    });
  }

  logout(): void {
    this.isLoading = true;
    this.authService.logout().subscribe({
      next: () => {
        this.isLoading = false;
        this.router.navigate(['/login']);
      },
      error: () => {
        this.isLoading = false;
        this.router.navigate(['/login']);
      }
    });
  }
}
