import { Routes } from '@angular/router';
import { LoginComponent } from './pages/login/login.component';
import { CadastroComponent } from './pages/cadastro/cadastro.component';
import { PerfilComponent } from './pages/perfil/perfil.component';
import { SobreNosComponent } from './pages/sobre-nos/sobre-nos.component';
import { TelaprodutoComponent } from './pages/telaproduto/telaproduto.component';
import { CatalogoComponent } from './pages/catalogo/catalogo.component';
import { PesquisaComponent } from './pages/pesquisa/pesquisa.component';

export const routes: Routes = [
  { path: '', redirectTo: 'login', pathMatch: 'full' },
  { path: 'login', component: LoginComponent },
  { path: 'cadastro', component: CadastroComponent },
  { path: 'perfil', component: PerfilComponent },
  { path: 'sobre-nos', component: SobreNosComponent },
  { path: 'catalogo', component: CatalogoComponent },
  { path: 'pesquisa', component: PesquisaComponent },
  { path: 'telaproduto/:id', loadComponent: () => import('./pages/telaproduto/telaproduto.component').then(m => m.TelaprodutoComponent) }
];
