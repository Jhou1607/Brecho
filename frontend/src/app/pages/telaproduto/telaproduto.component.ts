import { Component, OnInit } from '@angular/core';
import { CommonModule, CurrencyPipe } from '@angular/common';
import { HeaderComponent } from '../../shared/components/header/header.component';
import { FooterComponent } from '../../shared/components/footer/footer.component';
import { ProdutoService, Product } from '../../services/product.service';
import { ActivatedRoute, Router } from '@angular/router';
import { Observable, of } from 'rxjs';
import { catchError, switchMap, tap } from 'rxjs/operators';

@Component({
  selector: 'app-telaproduto',
  templateUrl: './telaproduto.component.html',
  styleUrls: ['./telaproduto.component.scss'],
  standalone: true,
  imports: [CommonModule, CurrencyPipe, HeaderComponent, FooterComponent]
})
export class TelaprodutoComponent implements OnInit {
  product$: Observable<Product | null> | undefined;
  selectedImage: string | undefined;
  isLoading = false;
  error: string | null = null;

  constructor(
    private produtoService: ProdutoService,
    private route: ActivatedRoute,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.isLoading = true;
    this.product$ = this.route.paramMap.pipe(
      switchMap(params => {
        const id = params.get('id');
        if (id) {
          this.error = null;
          return this.produtoService.getProdutoById(id).pipe(
            tap(product => {
              this.isLoading = false;
              if (product && product.images && product.images.length > 0) {
                this.selectedImage = this.getImagemUrl(product.images[0].url);
              } else if (product) {
                this.selectedImage = 'assets/placeholder-product.png';
              }
            }),
            catchError(err => {
              this.error = 'Produto não encontrado ou erro ao carregar.';
              this.isLoading = false;
              return of(null);
            })
          );
        } else {
          this.isLoading = false;
          this.error = 'ID do produto não fornecido na rota.';
          return of(null);
        }
      })
    );
  }

  selectImage(imageUrl: string | undefined | null): void {
    this.selectedImage = this.getImagemUrl(imageUrl);
  }

  addToFavorites(product: Product | null): void {
    if (!product) return;
    alert('Adicionar aos favoritos: ' + product.nome_produto);
  }

  excluirPeca(productId: number | undefined): void {
    if (productId === undefined) return;
    alert('Excluir peça com ID: ' + productId);
  }

  getImagemUrl(urlFromApi: string | undefined | null): string {
    return urlFromApi || 'assets/placeholder-product.png';
  }
}
