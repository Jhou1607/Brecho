import { Component, OnInit } from '@angular/core';
import { HeaderComponent } from '../../shared/components/header/header.component';
import { FooterComponent } from '../../shared/components/footer/footer.component';
import { FontAwesomeModule } from '@fortawesome/angular-fontawesome';
import { faHeart as faRegularHeart } from '@fortawesome/free-regular-svg-icons';
import { ProdutoService, Product, PaginatedResponse } from '../../services/product.service';
import { Router } from '@angular/router';
import { Observable, of } from 'rxjs';
import { tap, catchError, map } from 'rxjs/operators';
import { CommonModule} from '@angular/common';

@Component({
  selector: 'app-catalogo',
  standalone: true,
  imports: [
    CommonModule,
    HeaderComponent,
    FooterComponent,
    FontAwesomeModule,
  ],
  templateUrl: './catalogo.component.html',
  styleUrls: ['./catalogo.component.scss']
})
export class CatalogoComponent implements OnInit {
  faRegularHeart = faRegularHeart;
  produtos$: Observable<PaginatedResponse<Product>> | undefined;
  currentPage: number = 1;
  lastPage: number = 1;
  isLoading = false;
  error: string | null = null;

  constructor(private produtoService: ProdutoService, private router: Router) {}

  ngOnInit(): void {
    this.loadProducts();
  }

  loadProducts(): void {
    this.isLoading = true;
    this.error = null;
    this.produtos$ = this.produtoService.getProdutos(undefined, this.currentPage).pipe(
      tap({
        next: (response: PaginatedResponse<Product>) => {
          this.currentPage = response.current_page;
          this.lastPage = response.last_page;
          this.isLoading = false;
        },
        error: (err) => {
          this.error = 'Não foi possível carregar os produtos. Tente novamente mais tarde.';
          this.isLoading = false;
        }
      }),
      catchError(err => {
        this.error = 'Erro ao carregar o catálogo.';
        this.isLoading = false;
        return of({ data: [], current_page: 1, last_page: 1, per_page: 0, total: 0, links: [] } as PaginatedResponse<Product>);
      })
    );
  }

  viewProduct(product: Product): void {
    this.router.navigate(['/telaproduto', product.id]);
  }

  toggleFavorite(productId: number, event: Event): void {
    event.stopPropagation();
  }

  changePage(page: number): void {
    this.currentPage = page;
    this.loadProducts();
  }

  getImagemUrl(urlFromApi: string | undefined | null): string {
    return urlFromApi || 'assets/placeholder-product.png';
  }
}
