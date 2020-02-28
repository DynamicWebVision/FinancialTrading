<?php namespace App\Services;

use \App\Model\StrategyLog;
use \App\Model\StrategyLogIndicators;
use \App\Services\Scraper;
use App\Model\Marketing\ScrapedLeads;
use Twilio;

class Recruiters  {

    public function sendTextMessage() {
        $file = '<div id="recruiters_content_left" class="all-recruiters-test">
								
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2012/amanda-beachy/"><div class="photo" style="background-image:url(\'/photos/recruiters/1564690094.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2012/amanda-beachy/" itemprop="name">Amanda Beachy</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1564690094.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2012/amanda-beachy/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2011/amanda-beachy/"><div class="photo" style="background-image:url(\'/photos/recruiters/1564610390.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2011/amanda-beachy/" itemprop="name">Amanda Beachy</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1564610390.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2011/amanda-beachy/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1787/eileen-crosby/"><div class="photo" style="background-image:url(\'/photos/recruiters/1543849522.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1787/eileen-crosby/" itemprop="name">Eileen Crosby</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1543849522.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1787/eileen-crosby/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1517/juanita-davidson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1538599786.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1517/juanita-davidson/" itemprop="name">Juanita Davidson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							6 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1538599786.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1517/juanita-davidson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1634/lora-deane/"><div class="photo" style="background-image:url(\'/photos/recruiters/1531229778.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1634/lora-deane/" itemprop="name">Lora Deane</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1531229778.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1634/lora-deane/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1817/jen-dickman/"><div class="photo" style="background-image:url(\'/photos/recruiters/1547668485.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1817/jen-dickman/" itemprop="name">Jen Dickman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1547668485.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1817/jen-dickman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1658/taylor-frick/"><div class="photo" style="background-image:url(\'/photos/recruiters/1533062434.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1658/taylor-frick/" itemprop="name">Taylor Frick</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1533062434.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1658/taylor-frick/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1015/kari-goodchild/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1015/kari-goodchild/" itemprop="name">Kari Goodchild</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1015/kari-goodchild/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/758/erica-gordon/"><div class="photo" style="background-image:url(\'/photos/recruiters/1487188513.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/758/erica-gordon/" itemprop="name">Erica  Gordon</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							9 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1487188513.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/758/erica-gordon/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1028/marc-hester/"><div class="photo" style="background-image:url(\'/photos/recruiters/1487846824.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1028/marc-hester/" itemprop="name">Marc Hester</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							16 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1487846824.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1028/marc-hester/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1769/hayley-johnson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1541804475.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1769/hayley-johnson/" itemprop="name">Hayley Johnson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							3 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1541804475.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1769/hayley-johnson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1078/krystle-johnston/"><div class="photo" style="background-image:url(\'/photos/recruiters/1491502687.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1078/krystle-johnston/" itemprop="name">Krystle Johnston</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							49 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1491502687.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1078/krystle-johnston/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1077/corry-klebold/"><div class="photo" style="background-image:url(\'/photos/recruiters/1491576495.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1077/corry-klebold/" itemprop="name">Corry  Klebold</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							11 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1491576495.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1077/corry-klebold/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1285/michelle-powell/"><div class="photo" style="background-image:url(\'/photos/recruiters/1504188541.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1285/michelle-powell/" itemprop="name">Michelle Powell</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							8 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1504188541.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1285/michelle-powell/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1402/lisa-ross/"><div class="photo" style="background-image:url(\'/photos/recruiters/1515692588.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1402/lisa-ross/" itemprop="name">Lisa  Ross</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							13 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1515692588.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1402/lisa-ross/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1013/paul-ulrich/"><div class="photo" style="background-image:url(\'/photos/recruiters/1487018475.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1013/paul-ulrich/" itemprop="name">Paul Ulrich</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1487018475.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1013/paul-ulrich/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1080/sammi-vonderschmidt/"><div class="photo" style="background-image:url(\'/photos/recruiters/1491579100.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1080/sammi-vonderschmidt/" itemprop="name">Sammi Vonderschmidt</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advanced Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							7 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1491579100.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1080/sammi-vonderschmidt/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1893/chris-ballay/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1893/chris-ballay/" itemprop="name">Chris Ballay</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advantage Medical Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1893/chris-ballay/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1889/joey-cirilo/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1889/joey-cirilo/" itemprop="name">Joey Cirilo</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advantage Medical Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1889/joey-cirilo/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/470/jesus-hernandez/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/470/jesus-hernandez/" itemprop="name">Jesus Hernandez</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advantage Medical Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/470/jesus-hernandez/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1890/jay-laine/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1890/jay-laine/" itemprop="name">Jay Laine</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advantage Medical Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1890/jay-laine/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/37/barbie-landwehr/"><div class="photo" style="background-image:url(\'/photos/recruiters/1429128239.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/37/barbie-landwehr/" itemprop="name">Barbie Landwehr</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advantage Medical Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1429128239.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/37/barbie-landwehr/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1896/tony-mares/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1896/tony-mares/" itemprop="name">Tony Mares</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advantage Medical Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1896/tony-mares/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1891/nancy-mumphrey/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1891/nancy-mumphrey/" itemprop="name">Nancy Mumphrey</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advantage Medical Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1891/nancy-mumphrey/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2088/jennifer-mckisic/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2088/jennifer-mckisic/" itemprop="name">Jennifer McKisic</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advantage RN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2088/jennifer-mckisic/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1798/leslie-andress/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1798/leslie-andress/" itemprop="name">Leslie Andress</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advantis Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1798/leslie-andress/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2010/ashley-augustus/"><div class="photo" style="background-image:url(\'/photos/recruiters/1564608495.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2010/ashley-augustus/" itemprop="name">Ashley Augustus</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advantis Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1564608495.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2010/ashley-augustus/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1807/danielle-kaafarani/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1807/danielle-kaafarani/" itemprop="name">Danielle Kaafarani</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advantis Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1807/danielle-kaafarani/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1799/jimmy-tanner/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1799/jimmy-tanner/" itemprop="name">Jimmy Tanner</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Advantis Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1799/jimmy-tanner/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/376/jennifer-chudnoff/"><div class="photo" style="background-image:url(\'/photos/recruiters/1443108695.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/376/jennifer-chudnoff/" itemprop="name">Jennifer Chudnoff</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">AHS NurseStat</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							8 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1443108695.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/376/jennifer-chudnoff/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/36/marta-paz/"><div class="photo" style="background-image:url(\'/photos/recruiters/1426715812.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/36/marta-paz/" itemprop="name">Marta Paz</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">AHS NurseStat</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							5 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1426715812.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/36/marta-paz/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1835/stevie-aaron/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1835/stevie-aaron/" itemprop="name">Stevie Aaron</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">All Medical Personnel</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1835/stevie-aaron/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1318/morgan-agan/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1318/morgan-agan/" itemprop="name">Morgan  Agan</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">All Medical Personnel</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1318/morgan-agan/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1820/justin-hess/"><div class="photo" style="background-image:url(\'/photos/recruiters/1548089992.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1820/justin-hess/" itemprop="name">Justin Hess</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Alto HealthCare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1548089992.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1820/justin-hess/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2118/ellen-linville/"><div class="photo" style="background-image:url(\'/photos/recruiters/1575907719.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2118/ellen-linville/" itemprop="name">Ellen Linville</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Alto HealthCare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							8 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1575907719.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2118/ellen-linville/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1826/tracey-logan/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1826/tracey-logan/" itemprop="name">Tracey Logan</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Alto HealthCare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1826/tracey-logan/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2192/justine-horner/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2192/justine-horner/" itemprop="name">Justine Horner</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Amare Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2192/justine-horner/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2193/heather-sloss/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2193/heather-sloss/" itemprop="name">Heather Sloss</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Amare Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2193/heather-sloss/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2191/christian-williams/"><div class="photo" style="background-image:url(\'/photos/recruiters/1580483869.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2191/christian-williams/" itemprop="name">Christian Williams</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Amare Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1580483869.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2191/christian-williams/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2120/savannah-wood/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2120/savannah-wood/" itemprop="name">Savannah Wood</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Analytics Medical Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2120/savannah-wood/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1901/stephen-wood/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1901/stephen-wood/" itemprop="name">stephen Wood</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Analytics Medical Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1901/stephen-wood/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1287/kimberly-bardales/"><div class="photo" style="background-image:url(\'/photos/recruiters/1508272281.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1287/kimberly-bardales/" itemprop="name">Kimberly Bardales</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1508272281.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1287/kimberly-bardales/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1965/kait-bartoletta/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1965/kait-bartoletta/" itemprop="name">Kait Bartoletta</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1965/kait-bartoletta/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/986/stacy-bello/"><div class="photo" style="background-image:url(\'/photos/recruiters/1485987770.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/986/stacy-bello/" itemprop="name">Stacy Bello</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1485987770.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/986/stacy-bello/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1973/sunshine-betancourt-tindel/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1973/sunshine-betancourt-tindel/" itemprop="name">Sunshine Betancourt-Tindel</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1973/sunshine-betancourt-tindel/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2076/courtney-bookman/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2076/courtney-bookman/" itemprop="name">Courtney Bookman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2076/courtney-bookman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/979/ethan-collamer/"><div class="photo" style="background-image:url(\'/photos/recruiters/1485987873.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/979/ethan-collamer/" itemprop="name">Ethan Collamer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1485987873.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/979/ethan-collamer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/985/nicole-cox/"><div class="photo" style="background-image:url(\'/photos/recruiters/1485987943.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/985/nicole-cox/" itemprop="name">Nicole Cox</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1485987943.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/985/nicole-cox/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2163/myesha-daniels/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2163/myesha-daniels/" itemprop="name">Myesha Daniels</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2163/myesha-daniels/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1500/anthony-dickerson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1521222774.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1500/anthony-dickerson/" itemprop="name">Anthony Dickerson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1521222774.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1500/anthony-dickerson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/982/lindsay-evans/"><div class="photo" style="background-image:url(\'/photos/recruiters/1485987993.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/982/lindsay-evans/" itemprop="name">Lindsay Evans</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1485987993.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/982/lindsay-evans/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/977/brent-gallagher/"><div class="photo" style="background-image:url(\'/photos/recruiters/1485988050.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/977/brent-gallagher/" itemprop="name">Brent Gallagher</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1485988050.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/977/brent-gallagher/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1598/max-gordon/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1598/max-gordon/" itemprop="name">Max Gordon</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1598/max-gordon/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/984/ryan-hughes/"><div class="photo" style="background-image:url(\'/photos/recruiters/1485988238.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/984/ryan-hughes/" itemprop="name">Ryan Hughes</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1485988238.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/984/ryan-hughes/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1969/ruba-jamal/"><div class="photo" style="background-image:url(\'/photos/recruiters/1557770849.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1969/ruba-jamal/" itemprop="name">Ruba Jamal</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1557770849.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1969/ruba-jamal/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2164/angelo-ramirez/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2164/angelo-ramirez/" itemprop="name">Angelo Ramirez</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2164/angelo-ramirez/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/980/kelly-schrader/"><div class="photo" style="background-image:url(\'/photos/recruiters/1485988367.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/980/kelly-schrader/" itemprop="name">Kelly Schrader</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1485988367.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/980/kelly-schrader/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1411/melissa-stewart/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1411/melissa-stewart/" itemprop="name">Melissa Stewart</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1411/melissa-stewart/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2162/tony-turlington/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2162/tony-turlington/" itemprop="name">Tony Turlington</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Anders Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2162/tony-turlington/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/635/lena-finocchiaro/"><div class="photo" style="background-image:url(\'/photos/recruiters/1460752384.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/635/lena-finocchiaro/" itemprop="name">Lena Finocchiaro</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Aureus Medical Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1460752384.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/635/lena-finocchiaro/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/633/ryan-royse/"><div class="photo" style="background-image:url(\'/photos/recruiters/1460750601.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/633/ryan-royse/" itemprop="name">Ryan Royse</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Aureus Medical Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1460750601.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/633/ryan-royse/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/634/matt-sernett/"><div class="photo" style="background-image:url(\'/photos/recruiters/1460752367.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/634/matt-sernett/" itemprop="name">Matt Sernett</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Aureus Medical Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1460752367.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/634/matt-sernett/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/668/kylee-wilson-kemp/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/668/kylee-wilson-kemp/" itemprop="name">Kylee Wilson Kemp</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Aureus Medical Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/668/kylee-wilson-kemp/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2077/harold-banks/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2077/harold-banks/" itemprop="name">Harold Banks</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2077/harold-banks/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2091/martin-bowden/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2091/martin-bowden/" itemprop="name">Martin Bowden</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2091/martin-bowden/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2106/toni-corbin/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2106/toni-corbin/" itemprop="name">Toni Corbin</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2106/toni-corbin/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1904/chalonda-cotton/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1904/chalonda-cotton/" itemprop="name">Chalonda Cotton</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1904/chalonda-cotton/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2158/bailey-crosnoe/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2158/bailey-crosnoe/" itemprop="name">Bailey Crosnoe</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2158/bailey-crosnoe/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2092/andrew-durfee/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2092/andrew-durfee/" itemprop="name">Andrew Durfee</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2092/andrew-durfee/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2090/ashley-garza/"><div class="photo" style="background-image:url(\'/photos/recruiters/1572029139.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2090/ashley-garza/" itemprop="name">Ashley Garza</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1572029139.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2090/ashley-garza/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2174/kathleen-griffin/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2174/kathleen-griffin/" itemprop="name">Kathleen Griffin</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2174/kathleen-griffin/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1948/marco-juarez/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1948/marco-juarez/" itemprop="name">Marco Juarez</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1948/marco-juarez/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2198/charity-lebron/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2198/charity-lebron/" itemprop="name">Charity Lebron</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2198/charity-lebron/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2061/reesie-manchac/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2061/reesie-manchac/" itemprop="name">Reesie Manchac</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2061/reesie-manchac/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2059/ryan-parks/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2059/ryan-parks/" itemprop="name">Ryan Parks</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2059/ryan-parks/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2093/erica-pegues/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2093/erica-pegues/" itemprop="name">Erica Pegues</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2093/erica-pegues/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2053/cathy-phillips/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2053/cathy-phillips/" itemprop="name">Cathy Phillips</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2053/cathy-phillips/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2159/sherrie-runyan/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2159/sherrie-runyan/" itemprop="name">Sherrie Runyan</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2159/sherrie-runyan/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2078/blanca-segura/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2078/blanca-segura/" itemprop="name">Blanca Segura</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2078/blanca-segura/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1907/gabrael-smallwood/"><div class="photo" style="background-image:url(\'/photos/recruiters/1552338906.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1907/gabrael-smallwood/" itemprop="name">Gabrael  Smallwood</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1552338906.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1907/gabrael-smallwood/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2197/akaiya-thomas/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581015907.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2197/akaiya-thomas/" itemprop="name">Akaiya Thomas</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581015907.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2197/akaiya-thomas/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2117/copeland-tinney/"><div class="photo" style="background-image:url(\'/photos/recruiters/1575395344.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2117/copeland-tinney/" itemprop="name">Copeland Tinney</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1575395344.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2117/copeland-tinney/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2079/ashley-trivitt/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2079/ashley-trivitt/" itemprop="name">Ashley Trivitt</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CareerStaff Unlimited</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2079/ashley-trivitt/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2253/angie-ariza/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2253/angie-ariza/" itemprop="name">Angie  Ariza</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Cariant Health Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2253/angie-ariza/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1516/aaron-cohen/"><div class="photo" style="background-image:url(\'/photos/recruiters/1528318014.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1516/aaron-cohen/" itemprop="name">Aaron Cohen</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Cariant Health Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1528318014.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1516/aaron-cohen/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/46/tracey-duke/"><div class="photo" style="background-image:url(\'/photos/recruiters/1429128270.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/46/tracey-duke/" itemprop="name">Tracey Duke</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Cariant Health Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1429128270.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/46/tracey-duke/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1979/clint-elliott/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1979/clint-elliott/" itemprop="name">Clint Elliott</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Cariant Health Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1979/clint-elliott/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1873/jennifer-flott/"><div class="photo" style="background-image:url(\'/photos/recruiters/1559330543.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1873/jennifer-flott/" itemprop="name">Jennifer Flott</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Cariant Health Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1559330543.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1873/jennifer-flott/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1563/richard-goll/"><div class="photo" style="background-image:url(\'/photos/recruiters/1528318097.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1563/richard-goll/" itemprop="name">Richard Goll</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Cariant Health Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1528318097.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1563/richard-goll/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1978/scott-hoffman/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1978/scott-hoffman/" itemprop="name">Scott Hoffman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Cariant Health Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1978/scott-hoffman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/49/tony-marino/"><div class="photo" style="background-image:url(\'/photos/recruiters/1528318169.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/49/tony-marino/" itemprop="name">Tony Marino</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Cariant Health Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1528318169.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/49/tony-marino/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/613/kim-neubauer/"><div class="photo" style="background-image:url(\'/photos/recruiters/1460580893.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/613/kim-neubauer/" itemprop="name">Kim Neubauer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Cariant Health Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1460580893.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/613/kim-neubauer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2137/tiffani-sailes/"><div class="photo" style="background-image:url(\'/photos/recruiters/1577977128.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2137/tiffani-sailes/" itemprop="name">Tiffani Sailes</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Cariant Health Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1577977128.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2137/tiffani-sailes/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2138/bailey-schollmeyer/"><div class="photo" style="background-image:url(\'/photos/recruiters/1577977005.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2138/bailey-schollmeyer/" itemprop="name">Bailey Schollmeyer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Cariant Health Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1577977005.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2138/bailey-schollmeyer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/47/becky-sullivan/"><div class="photo" style="background-image:url(\'/photos/recruiters/1483730310.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/47/becky-sullivan/" itemprop="name">Becky Sullivan</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Cariant Health Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1483730310.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/47/becky-sullivan/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/53/lisa-willert/"><div class="photo" style="background-image:url(\'/photos/recruiters/1483729836.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/53/lisa-willert/" itemprop="name">Lisa Willert</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Cariant Health Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1483729836.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/53/lisa-willert/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2075/stephanie-dixon/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2075/stephanie-dixon/" itemprop="name">Stephanie Dixon</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Concentric Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2075/stephanie-dixon/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/595/cola-heesacker/"><div class="photo" style="background-image:url(\'/photos/recruiters/1547483254.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/595/cola-heesacker/" itemprop="name">Cola Heesacker</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Concentric Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							16 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1547483254.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/595/cola-heesacker/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/411/nick-jimenez/"><div class="photo" style="background-image:url(\'/photos/recruiters/1443713872.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/411/nick-jimenez/" itemprop="name">Nick Jimenez</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Concentric Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							11 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1443713872.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/411/nick-jimenez/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2084/kevin-rawson/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2084/kevin-rawson/" itemprop="name">Kevin Rawson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Concentric Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2084/kevin-rawson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/410/george-rhoades/"><div class="photo" style="background-image:url(\'/photos/recruiters/1450384531.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/410/george-rhoades/" itemprop="name">George Rhoades</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Concentric Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							11 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1450384531.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/410/george-rhoades/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2187/casey-stensland/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2187/casey-stensland/" itemprop="name">Casey Stensland</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Concentric Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2187/casey-stensland/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/880/cary-farrow/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/880/cary-farrow/" itemprop="name">Cary Farrow</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Continuum Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/880/cary-farrow/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/879/lacey-gifford/"><div class="photo" style="background-image:url(\'/photos/recruiters/1478629817.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/879/lacey-gifford/" itemprop="name">Lacey Gifford</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Continuum Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1478629817.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/879/lacey-gifford/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1125/tara-johnson/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1125/tara-johnson/" itemprop="name">Tara Johnson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Continuum Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1125/tara-johnson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1961/gina-smith/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1961/gina-smith/" itemprop="name">Gina Smith</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Continuum Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1961/gina-smith/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1124/veronica-tigges/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1124/veronica-tigges/" itemprop="name">Veronica Tigges</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Continuum Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1124/veronica-tigges/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1975/dottie-hall/"><div class="photo" style="background-image:url(\'/photos/recruiters/1558460393.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1975/dottie-hall/" itemprop="name">Dottie Hall</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Convergence Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1558460393.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1975/dottie-hall/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1955/john-lancaster/"><div class="photo" style="background-image:url(\'/photos/recruiters/1556565283.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1955/john-lancaster/" itemprop="name">John Lancaster</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Convergence Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1556565283.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1955/john-lancaster/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/882/christie-mcgee/"><div class="photo" style="background-image:url(\'/photos/recruiters/1556629869.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/882/christie-mcgee/" itemprop="name">Christie McGee</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Convergence Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1556629869.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/882/christie-mcgee/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2119/liz-moua/"><div class="photo" style="background-image:url(\'/photos/recruiters/1575488968.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2119/liz-moua/" itemprop="name">Liz Moua</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Convergence Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1575488968.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2119/liz-moua/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1956/brian-sipe/"><div class="photo" style="background-image:url(\'/photos/recruiters/1556565233.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1956/brian-sipe/" itemprop="name">Brian Sipe</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Convergence Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1556565233.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1956/brian-sipe/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1523/erin-ford/"><div class="photo" style="background-image:url(\'/photos/recruiters/1523906718.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1523/erin-ford/" itemprop="name">Erin Ford</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Core Medical Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1523906718.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1523/erin-ford/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/72/kara-gershman/"><div class="photo" style="background-image:url(\'/photos/recruiters/1523906761.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/72/kara-gershman/" itemprop="name">Kara Gershman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Core Medical Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1523906761.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/72/kara-gershman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/75/ryan-harrington/"><div class="photo" style="background-image:url(\'/photos/recruiters/1523906778.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/75/ryan-harrington/" itemprop="name">Ryan Harrington</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Core Medical Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1523906778.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/75/ryan-harrington/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1171/charles-hernandez/"><div class="photo" style="background-image:url(\'/photos/recruiters/1523906808.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1171/charles-hernandez/" itemprop="name">Charles Hernandez</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Core Medical Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1523906808.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1171/charles-hernandez/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1174/spencer-jackson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1523906841.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1174/spencer-jackson/" itemprop="name">Spencer Jackson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Core Medical Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1523906841.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1174/spencer-jackson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/931/rob-lasher/"><div class="photo" style="background-image:url(\'/photos/recruiters/1523387844.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/931/rob-lasher/" itemprop="name">Rob Lasher</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Core Medical Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1523387844.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/931/rob-lasher/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/70/derek-leo/"><div class="photo" style="background-image:url(\'/photos/recruiters/1523906902.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/70/derek-leo/" itemprop="name">Derek Leo</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Core Medical Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							3 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1523906902.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/70/derek-leo/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/68/ted-maravelias/"><div class="photo" style="background-image:url(\'/photos/recruiters/1465406256.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/68/ted-maravelias/" itemprop="name">Ted Maravelias</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Core Medical Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1465406256.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/68/ted-maravelias/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/73/fred-whittle/"><div class="photo" style="background-image:url(\'/photos/recruiters/1465313087.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/73/fred-whittle/" itemprop="name">Fred Whittle</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Core Medical Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1465313087.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/73/fred-whittle/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2241/katie-rapin/"><div class="photo" style="background-image:url(\'/photos/recruiters/1582136080.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2241/katie-rapin/" itemprop="name">Katie Rapin</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CORE Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1582136080.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2241/katie-rapin/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2242/lisa-stadler/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2242/lisa-stadler/" itemprop="name">lisa stadler</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CORE Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2242/lisa-stadler/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1559/melanie-simon/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1559/melanie-simon/" itemprop="name">Melanie Simon</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Cross Country Nurses</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1559/melanie-simon/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2132/kimberly-brandau/"><div class="photo" style="background-image:url(\'/photos/recruiters/1576868499.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2132/kimberly-brandau/" itemprop="name">Kimberly  Brandau</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">CrossMed Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1576868499.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2132/kimberly-brandau/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1957/zach-sansone/"><div class="photo" style="background-image:url(\'/photos/recruiters/1556641966.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1957/zach-sansone/" itemprop="name">Zach Sansone</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Destination Travelcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1556641966.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1957/zach-sansone/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2142/alexa-barton/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2142/alexa-barton/" itemprop="name">Alexa  Barton</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2142/alexa-barton/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2154/randi-daniel/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2154/randi-daniel/" itemprop="name">Randi Daniel</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2154/randi-daniel/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2149/danielle-gasca/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2149/danielle-gasca/" itemprop="name">Danielle Gasca</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2149/danielle-gasca/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2151/emily-goodman/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2151/emily-goodman/" itemprop="name">Emily Goodman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2151/emily-goodman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2143/amanda-grant/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2143/amanda-grant/" itemprop="name">Amanda Grant</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2143/amanda-grant/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1848/valarie-hawkins/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1848/valarie-hawkins/" itemprop="name">Valarie Hawkins</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1848/valarie-hawkins/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1849/felicia-hess/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1849/felicia-hess/" itemprop="name">Felicia Hess</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1849/felicia-hess/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1847/kristen-jewell/"><div class="photo" style="background-image:url(\'/photos/recruiters/1548887142.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1847/kristen-jewell/" itemprop="name">Kristen Jewell</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1548887142.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1847/kristen-jewell/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1844/aleah-kahn/"><div class="photo" style="background-image:url(\'/photos/recruiters/1548887055.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1844/aleah-kahn/" itemprop="name">Aleah Kahn</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1548887055.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1844/aleah-kahn/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2152/lauren-leonard/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2152/lauren-leonard/" itemprop="name">Lauren  Leonard</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2152/lauren-leonard/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2144/anne-lievens/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2144/anne-lievens/" itemprop="name">Anne  Lievens</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2144/anne-lievens/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2145/bonnie-mcfarland/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2145/bonnie-mcfarland/" itemprop="name">Bonnie McFarland</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2145/bonnie-mcfarland/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2150/danny-moore/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2150/danny-moore/" itemprop="name">Danny  Moore</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2150/danny-moore/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2155/shaquila-parrish/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2155/shaquila-parrish/" itemprop="name">Shaquila Parrish</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2155/shaquila-parrish/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2153/martin-ramos/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2153/martin-ramos/" itemprop="name">Martin Ramos</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2153/martin-ramos/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2147/christina-rangel/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2147/christina-rangel/" itemprop="name">Christina Rangel</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2147/christina-rangel/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1845/callie-sapp/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1845/callie-sapp/" itemprop="name">Callie Sapp</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1845/callie-sapp/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2148/copper-siegel/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2148/copper-siegel/" itemprop="name">Copper Siegel</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2148/copper-siegel/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1846/jessica-wynn/"><div class="photo" style="background-image:url(\'/photos/recruiters/1548945203.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1846/jessica-wynn/" itemprop="name">Jessica Wynn</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Favorite Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1548945203.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1846/jessica-wynn/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2252/rain-de-guzman/"><div class="photo" style="background-image:url(\'/photos/recruiters/1582661912.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2252/rain-de-guzman/" itemprop="name">RAIN DE GUZMAN</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">First Choice Nurses of Eastern Virginia</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1582661912.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2252/rain-de-guzman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2243/patrick-jackowski/"><div class="photo" style="background-image:url(\'/photos/recruiters/1582317812.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2243/patrick-jackowski/" itemprop="name">Patrick Jackowski</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">First Choice Nurses of Eastern Virginia</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1582317812.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2243/patrick-jackowski/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2244/bo-lomax/"><div class="photo" style="background-image:url(\'/photos/recruiters/1582317644.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2244/bo-lomax/" itemprop="name">Bo Lomax</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">First Choice Nurses of Eastern Virginia</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1582317644.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2244/bo-lomax/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2251/frances-martin/"><div class="photo" style="background-image:url(\'/photos/recruiters/1582662064.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2251/frances-martin/" itemprop="name">Frances Martin</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">First Choice Nurses of Eastern Virginia</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1582662064.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2251/frances-martin/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1582/jen-dempsey/"><div class="photo" style="background-image:url(\'/photos/recruiters/1525881763.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1582/jen-dempsey/" itemprop="name">Jen Dempsey</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">FlexCare Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							6 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1525881763.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1582/jen-dempsey/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1580/harrison-montague/"><div class="photo" style="background-image:url(\'/photos/recruiters/1525801233.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1580/harrison-montague/" itemprop="name">Harrison Montague</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">FlexCare Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1525801233.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1580/harrison-montague/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1581/nate-shanklin/"><div class="photo" style="background-image:url(\'/photos/recruiters/1525880741.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1581/nate-shanklin/" itemprop="name">Nate Shanklin</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">FlexCare Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							3 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1525880741.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1581/nate-shanklin/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2134/shay-khorramshahi/"><div class="photo" style="background-image:url(\'/photos/recruiters/1580508352.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2134/shay-khorramshahi/" itemprop="name">Shay Khorramshahi</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Focus Staff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1580508352.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2134/shay-khorramshahi/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2133/dalena-le/"><div class="photo" style="background-image:url(\'/photos/recruiters/1578410619.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2133/dalena-le/" itemprop="name">Dalena Le</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Focus Staff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1578410619.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2133/dalena-le/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2135/taylar-mata/"><div class="photo" style="background-image:url(\'/photos/recruiters/1578428820.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2135/taylar-mata/" itemprop="name">Taylar Mata</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Focus Staff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1578428820.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2135/taylar-mata/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2136/garrett-matacale/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2136/garrett-matacale/" itemprop="name">Garrett Matacale</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Focus Staff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2136/garrett-matacale/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2036/alyssa-falbo/"><div class="photo" style="background-image:url(\'/photos/recruiters/1566228592.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2036/alyssa-falbo/" itemprop="name">Alyssa Falbo</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fortus Healthcare Resources</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							9 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1566228592.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2036/alyssa-falbo/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2037/rebecca-fernandes/"><div class="photo" style="background-image:url(\'/photos/recruiters/1566227037.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2037/rebecca-fernandes/" itemprop="name">Rebecca Fernandes</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fortus Healthcare Resources</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							12 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1566227037.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2037/rebecca-fernandes/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2038/janet-jones/"><div class="photo" style="background-image:url(\'/photos/recruiters/1566418965.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2038/janet-jones/" itemprop="name">Janet Jones</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fortus Healthcare Resources</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							8 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1566418965.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2038/janet-jones/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2039/edward-mackenzie/"><div class="photo" style="background-image:url(\'/photos/recruiters/1566403106.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2039/edward-mackenzie/" itemprop="name">Edward MacKenzie</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fortus Healthcare Resources</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1566403106.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2039/edward-mackenzie/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2040/nikeeda-marshall-wilson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1573665169.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2040/nikeeda-marshall-wilson/" itemprop="name">Nikeeda Marshall-Wilson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fortus Healthcare Resources</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							13 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1573665169.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2040/nikeeda-marshall-wilson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2041/patrice-may/"><div class="photo" style="background-image:url(\'/photos/recruiters/1566227054.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2041/patrice-may/" itemprop="name">Patrice May</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fortus Healthcare Resources</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							5 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1566227054.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2041/patrice-may/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2042/cody-mooney/"><div class="photo" style="background-image:url(\'/photos/recruiters/1566845020.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2042/cody-mooney/" itemprop="name">Cody Mooney</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fortus Healthcare Resources</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1566845020.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2042/cody-mooney/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2043/mackenzie-morgan/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581970705.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2043/mackenzie-morgan/" itemprop="name">Mackenzie Morgan</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fortus Healthcare Resources</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							14 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581970705.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2043/mackenzie-morgan/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2057/simone-palumbo/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2057/simone-palumbo/" itemprop="name">Simone Palumbo</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fortus Healthcare Resources</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2057/simone-palumbo/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1090/carrie-ausdemore/"><div class="photo" style="background-image:url(\'/photos/recruiters/1492623120.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1090/carrie-ausdemore/" itemprop="name">Carrie  Ausdemore</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1492623120.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1090/carrie-ausdemore/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1741/jake-berglund/"><div class="photo" style="background-image:url(\'/photos/recruiters/1543444152.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1741/jake-berglund/" itemprop="name">Jake Berglund</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							12 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1543444152.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1741/jake-berglund/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1750/amy-campbell/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1750/amy-campbell/" itemprop="name">Amy Campbell</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1750/amy-campbell/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1100/jessica-casper/"><div class="photo" style="background-image:url(\'/photos/recruiters/1540228185.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1100/jessica-casper/" itemprop="name">Jessica Casper</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							38 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1540228185.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1100/jessica-casper/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1098/jeff-chambers/"><div class="photo" style="background-image:url(\'/photos/recruiters/1539807081.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1098/jeff-chambers/" itemprop="name">Jeff Chambers</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1539807081.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1098/jeff-chambers/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1102/john-donohue/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1102/john-donohue/" itemprop="name">John Donohue</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1102/john-donohue/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1727/abby-eastman/"><div class="photo" style="background-image:url(\'/photos/recruiters/1539869528.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1727/abby-eastman/" itemprop="name">Abby Eastman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1539869528.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1727/abby-eastman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1088/aubrey-foley/"><div class="photo" style="background-image:url(\'/photos/recruiters/1540561612.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1088/aubrey-foley/" itemprop="name">Aubrey Foley</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							12 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1540561612.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1088/aubrey-foley/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1728/jess-gentile/"><div class="photo" style="background-image:url(\'/photos/recruiters/1539808920.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1728/jess-gentile/" itemprop="name">Jess Gentile</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1539808920.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1728/jess-gentile/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1725/molly-gottschalk/"><div class="photo" style="background-image:url(\'/photos/recruiters/1539797983.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1725/molly-gottschalk/" itemprop="name">Molly Gottschalk</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1539797983.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1725/molly-gottschalk/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1746/chris-gralheer/"><div class="photo" style="background-image:url(\'/photos/recruiters/1540568030.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1746/chris-gralheer/" itemprop="name">Chris Gralheer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1540568030.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1746/chris-gralheer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1094/courtney-grothaus/"><div class="photo" style="background-image:url(\'/photos/recruiters/1540561621.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1094/courtney-grothaus/" itemprop="name">Courtney Grothaus</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1540561621.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1094/courtney-grothaus/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1735/kendall-hazel/"><div class="photo" style="background-image:url(\'/photos/recruiters/1540316602.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1735/kendall-hazel/" itemprop="name">Kendall Hazel</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1540316602.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1735/kendall-hazel/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/33/jess-jackson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1540499885.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/33/jess-jackson/" itemprop="name">Jess Jackson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1540499885.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/33/jess-jackson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1089/becca-jacobs/"><div class="photo" style="background-image:url(\'/photos/recruiters/1492185822.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1089/becca-jacobs/" itemprop="name">Becca Jacobs</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1492185822.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1089/becca-jacobs/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1112/samantha-kastler/"><div class="photo" style="background-image:url(\'/photos/recruiters/1492535812.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1112/samantha-kastler/" itemprop="name">Samantha Kastler</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1492535812.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1112/samantha-kastler/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1744/kayla-kelley/"><div class="photo" style="background-image:url(\'/photos/recruiters/1540562358.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1744/kayla-kelley/" itemprop="name">Kayla  Kelley</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							5 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1540562358.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1744/kayla-kelley/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/22/tony-korth/"><div class="photo" style="background-image:url(\'/photos/recruiters/1429127965.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/22/tony-korth/" itemprop="name">Tony Korth</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1429127965.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/22/tony-korth/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1086/adam-lane/"><div class="photo" style="background-image:url(\'/photos/recruiters/1492431626.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1086/adam-lane/" itemprop="name">Adam Lane</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							19 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1492431626.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1086/adam-lane/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1104/kate-martinez/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1104/kate-martinez/" itemprop="name">Kate Martinez</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1104/kate-martinez/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1115/taina-mcdonald/"><div class="photo" style="background-image:url(\'/photos/recruiters/1492536579.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1115/taina-mcdonald/" itemprop="name">Taina McDonald</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							17 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1492536579.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1115/taina-mcdonald/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1755/jessica-mejia/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1755/jessica-mejia/" itemprop="name">Jessica Mejia</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1755/jessica-mejia/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1107/malea-melis/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1107/malea-melis/" itemprop="name">Malea Melis</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1107/malea-melis/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1092/cecilia-merrill/"><div class="photo" style="background-image:url(\'/photos/recruiters/1547135278.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1092/cecilia-merrill/" itemprop="name">Cecilia Merrill</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							6 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1547135278.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1092/cecilia-merrill/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1103/john-morrison/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1103/john-morrison/" itemprop="name">John Morrison</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1103/john-morrison/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1087/anne-munnelly/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1087/anne-munnelly/" itemprop="name">Anne Munnelly</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1087/anne-munnelly/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1709/ashtyn-neill/"><div class="photo" style="background-image:url(\'/photos/recruiters/1539790915.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1709/ashtyn-neill/" itemprop="name">Ashtyn Neill</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1539790915.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1709/ashtyn-neill/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1106/kelvin-nesbit/"><div class="photo" style="background-image:url(\'/photos/recruiters/1559915997.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1106/kelvin-nesbit/" itemprop="name">Kelvin Nesbit</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1559915997.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1106/kelvin-nesbit/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1095/dustin-ninemire/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1095/dustin-ninemire/" itemprop="name">Dustin Ninemire</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1095/dustin-ninemire/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1749/mitchel-petersen/"><div class="photo" style="background-image:url(\'/photos/recruiters/1540581427.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1749/mitchel-petersen/" itemprop="name">Mitchel Petersen</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1540581427.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1749/mitchel-petersen/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/34/raquel-pfannenstiel/"><div class="photo" style="background-image:url(\'/photos/recruiters/1426274037.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/34/raquel-pfannenstiel/" itemprop="name">Raquel Pfannenstiel</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							14 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1426274037.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/34/raquel-pfannenstiel/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1724/carrie-polak/"><div class="photo" style="background-image:url(\'/photos/recruiters/1539795300.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1724/carrie-polak/" itemprop="name">Carrie Polak</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							14 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1539795300.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1724/carrie-polak/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1743/tim-quass/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1743/tim-quass/" itemprop="name">Tim Quass</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1743/tim-quass/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1745/lacey-rador/"><div class="photo" style="background-image:url(\'/photos/recruiters/1540563937.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1745/lacey-rador/" itemprop="name">Lacey Rador</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1540563937.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1745/lacey-rador/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1747/nate-riley/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1747/nate-riley/" itemprop="name">Nate Riley</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1747/nate-riley/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1729/chase-saxton/"><div class="photo" style="background-image:url(\'/photos/recruiters/1539810965.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1729/chase-saxton/" itemprop="name">Chase Saxton</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1539810965.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1729/chase-saxton/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1105/katie-schlee/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1105/katie-schlee/" itemprop="name">Katie Schlee</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1105/katie-schlee/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1742/jessica-shipley/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1742/jessica-shipley/" itemprop="name">Jessica Shipley</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1742/jessica-shipley/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1748/heather-stevens/"><div class="photo" style="background-image:url(\'/photos/recruiters/1540570724.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1748/heather-stevens/" itemprop="name">Heather Stevens</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1540570724.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1748/heather-stevens/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1710/eric-vejvoda/"><div class="photo" style="background-image:url(\'/photos/recruiters/1540343195.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1710/eric-vejvoda/" itemprop="name">Eric Vejvoda</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1540343195.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1710/eric-vejvoda/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1730/scott-villotta/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1730/scott-villotta/" itemprop="name">Scott  Villotta</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1730/scott-villotta/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1762/chris-walker/"><div class="photo" style="background-image:url(\'/photos/recruiters/1541619057.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1762/chris-walker/" itemprop="name">Chris Walker</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1541619057.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1762/chris-walker/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1108/matt-waters/"><div class="photo" style="background-image:url(\'/photos/recruiters/1540571092.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1108/matt-waters/" itemprop="name">Matt Waters</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1540571092.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1108/matt-waters/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1726/kiley-winters/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1726/kiley-winters/" itemprop="name">Kiley Winters</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1726/kiley-winters/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1099/jennifer-yeshnowski/"><div class="photo" style="background-image:url(\'/photos/recruiters/1539801822.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1099/jennifer-yeshnowski/" itemprop="name">Jennifer Yeshnowski</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Fusion Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1539801822.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1099/jennifer-yeshnowski/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1763/kristi-hamilton/"><div class="photo" style="background-image:url(\'/photos/recruiters/1541193508.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1763/kristi-hamilton/" itemprop="name">Kristi Hamilton</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">GetMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1541193508.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1763/kristi-hamilton/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1764/linda-hotchkiss/"><div class="photo" style="background-image:url(\'/photos/recruiters/1541686490.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1764/linda-hotchkiss/" itemprop="name">Linda Hotchkiss</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">GetMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1541686490.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1764/linda-hotchkiss/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1963/emily-peterson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1557174226.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1963/emily-peterson/" itemprop="name">Emily Peterson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">GetMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1557174226.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1963/emily-peterson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/868/clarence-aikens/"><div class="photo" style="background-image:url(\'/photos/recruiters/1478118703.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/868/clarence-aikens/" itemprop="name">Clarence  Aikens</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1478118703.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/868/clarence-aikens/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/856/blake-alleman/"><div class="photo" style="background-image:url(\'/photos/recruiters/1478113577.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/856/blake-alleman/" itemprop="name">Blake Alleman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							9 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1478113577.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/856/blake-alleman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1046/melissa-allmond/"><div class="photo" style="background-image:url(\'/photos/recruiters/1488908346.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1046/melissa-allmond/" itemprop="name">Melissa Allmond</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1488908346.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1046/melissa-allmond/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/863/brandy-anderson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1478114782.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/863/brandy-anderson/" itemprop="name">Brandy Anderson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1478114782.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/863/brandy-anderson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1043/lauren-boudreaux/"><div class="photo" style="background-image:url(\'/photos/recruiters/1488908249.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1043/lauren-boudreaux/" itemprop="name">Lauren Boudreaux</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1488908249.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1043/lauren-boudreaux/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1044/cosetta-bradford/"><div class="photo" style="background-image:url(\'/photos/recruiters/1488908072.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1044/cosetta-bradford/" itemprop="name">Cosetta Bradford</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1488908072.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1044/cosetta-bradford/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1047/teresa-clark/"><div class="photo" style="background-image:url(\'/photos/recruiters/1488908515.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1047/teresa-clark/" itemprop="name">Teresa Clark</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1488908515.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1047/teresa-clark/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/867/ashley-fuselier/"><div class="photo" style="background-image:url(\'/photos/recruiters/1478118379.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/867/ashley-fuselier/" itemprop="name">Ashley Fuselier</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1478118379.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/867/ashley-fuselier/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1004/erik-jacyshyn/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1004/erik-jacyshyn/" itemprop="name">Erik Jacyshyn</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1004/erik-jacyshyn/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/865/nicholas-james/"><div class="photo" style="background-image:url(\'/photos/recruiters/1478727479.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/865/nicholas-james/" itemprop="name">Nicholas James</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1478727479.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/865/nicholas-james/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/855/bill-peoples/"><div class="photo" style="background-image:url(\'/photos/recruiters/1478113648.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/855/bill-peoples/" itemprop="name">Bill Peoples</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1478113648.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/855/bill-peoples/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/858/holly-perry/"><div class="photo" style="background-image:url(\'/photos/recruiters/1478113830.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/858/holly-perry/" itemprop="name">Holly Perry</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1478113830.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/858/holly-perry/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/857/cathy-phillips/"><div class="photo" style="background-image:url(\'/photos/recruiters/1478113355.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/857/cathy-phillips/" itemprop="name">Cathy Phillips</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							5 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1478113355.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/857/cathy-phillips/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/872/nicole-pierpoint/"><div class="photo" style="background-image:url(\'/photos/recruiters/1478119282.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/872/nicole-pierpoint/" itemprop="name">Nicole Pierpoint</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1478119282.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/872/nicole-pierpoint/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1045/whitney-robison/"><div class="photo" style="background-image:url(\'/photos/recruiters/1488908139.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1045/whitney-robison/" itemprop="name">Whitney Robison</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1488908139.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1045/whitney-robison/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/871/melinda-rodeghero/"><div class="photo" style="background-image:url(\'/photos/recruiters/1478119166.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/871/melinda-rodeghero/" itemprop="name">Melinda Rodeghero</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1478119166.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/871/melinda-rodeghero/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/869/jennifer-saddy/"><div class="photo" style="background-image:url(\'/photos/recruiters/1478118977.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/869/jennifer-saddy/" itemprop="name">Jennifer Saddy</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Gifted Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1478118977.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/869/jennifer-saddy/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1437/andre-barnes/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1437/andre-barnes/" itemprop="name">Andre Barnes</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">GO Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1437/andre-barnes/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1438/craig-matijow/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1438/craig-matijow/" itemprop="name">Craig  Matijow</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">GO Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1438/craig-matijow/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/16/lauren-burger/"><div class="photo" style="background-image:url(\'/photos/recruiters/1541518636.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/16/lauren-burger/" itemprop="name">Lauren Burger</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Health Providers Choice</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1541518636.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/16/lauren-burger/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/160/carol-carter/"><div class="photo" style="background-image:url(\'/photos/recruiters/1541517747.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/160/carol-carter/" itemprop="name">Carol Carter</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Health Providers Choice</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1541517747.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/160/carol-carter/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1190/jason-daddario/"><div class="photo" style="background-image:url(\'/photos/recruiters/1496845607.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1190/jason-daddario/" itemprop="name">Jason D\'Addario</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Health Providers Choice</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1496845607.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1190/jason-daddario/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1971/matt-haack/"><div class="photo" style="background-image:url(\'/photos/recruiters/1557866223.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1971/matt-haack/" itemprop="name">Matt Haack</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Health Providers Choice</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1557866223.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1971/matt-haack/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/161/jeff-kleckler/"><div class="photo" style="background-image:url(\'/photos/recruiters/1432753771.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/161/jeff-kleckler/" itemprop="name">Jeff Kleckler</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Health Providers Choice</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1432753771.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/161/jeff-kleckler/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1187/kristina-lulgjuraj/"><div class="photo" style="background-image:url(\'/photos/recruiters/1496436257.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1187/kristina-lulgjuraj/" itemprop="name">Kristina Lulgjuraj</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Health Providers Choice</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1496436257.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1187/kristina-lulgjuraj/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1201/vicki-masouras/"><div class="photo" style="background-image:url(\'/photos/recruiters/1496948002.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1201/vicki-masouras/" itemprop="name">Vicki Masouras</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Health Providers Choice</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1496948002.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1201/vicki-masouras/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1197/kathy-mullin/"><div class="photo" style="background-image:url(\'/photos/recruiters/1496845694.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1197/kathy-mullin/" itemprop="name">Kathy Mullin</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Health Providers Choice</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1496845694.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1197/kathy-mullin/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2184/rob-ochmanski/"><div class="photo" style="background-image:url(\'/photos/recruiters/1580226192.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2184/rob-ochmanski/" itemprop="name">Rob Ochmanski</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Health Providers Choice</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1580226192.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2184/rob-ochmanski/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2183/marisa-panchenko/"><div class="photo" style="background-image:url(\'/photos/recruiters/1580225632.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2183/marisa-panchenko/" itemprop="name">Marisa Panchenko</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Health Providers Choice</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1580225632.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2183/marisa-panchenko/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/17/rosemarie-torrento/"><div class="photo" style="background-image:url(\'/photos/recruiters/1425917967.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/17/rosemarie-torrento/" itemprop="name">Rosemarie Torrento</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Health Providers Choice</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1425917967.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/17/rosemarie-torrento/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1292/claudia-carter/"><div class="photo" style="background-image:url(\'/photos/recruiters/1505837734.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1292/claudia-carter/" itemprop="name">Claudia Carter</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Health Specialists, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1505837734.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1292/claudia-carter/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1291/melanie-perry/"><div class="photo" style="background-image:url(\'/photos/recruiters/1505836727.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1291/melanie-perry/" itemprop="name">Melanie Perry</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Health Specialists, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1505836727.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1291/melanie-perry/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1288/mindy-stewart/"><div class="photo" style="background-image:url(\'/photos/recruiters/1505512500.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1288/mindy-stewart/" itemprop="name">Mindy  Stewart</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Health Specialists, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1505512500.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1288/mindy-stewart/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1278/maison-carmichael/"><div class="photo" style="background-image:url(\'/photos/recruiters/1507829975.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1278/maison-carmichael/" itemprop="name">Maison Carmichael</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1507829975.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1278/maison-carmichael/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1603/alex-crisler/"><div class="photo" style="background-image:url(\'/photos/recruiters/1561498699.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1603/alex-crisler/" itemprop="name">Alex Crisler</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1561498699.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1603/alex-crisler/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1424/carly-deluca/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1424/carly-deluca/" itemprop="name">Carly Deluca</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1424/carly-deluca/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/140/kaylan-duffy/"><div class="photo" style="background-image:url(\'/photos/recruiters/1432664444.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/140/kaylan-duffy/" itemprop="name">Kaylan Duffy</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1432664444.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/140/kaylan-duffy/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1219/kristie-ernau/"><div class="photo" style="background-image:url(\'/photos/recruiters/1507768370.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1219/kristie-ernau/" itemprop="name">Kristie Ernau</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1507768370.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1219/kristie-ernau/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1700/ashley-fairbanks/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1700/ashley-fairbanks/" itemprop="name">Ashley Fairbanks</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1700/ashley-fairbanks/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1604/tim-goelze/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1604/tim-goelze/" itemprop="name">Tim Goelze</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1604/tim-goelze/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1605/chelsea-guthrie/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1605/chelsea-guthrie/" itemprop="name">Chelsea Guthrie</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1605/chelsea-guthrie/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1329/brendan-haire/"><div class="photo" style="background-image:url(\'/photos/recruiters/1507830587.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1329/brendan-haire/" itemprop="name">Brendan Haire</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1507830587.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1329/brendan-haire/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1599/brittnee-harvey/"><div class="photo" style="background-image:url(\'/photos/recruiters/1559057412.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1599/brittnee-harvey/" itemprop="name">Brittnee Harvey</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1559057412.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1599/brittnee-harvey/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1392/hunter-hopkins/"><div class="photo" style="background-image:url(\'/photos/recruiters/1523570899.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1392/hunter-hopkins/" itemprop="name">Hunter Hopkins</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1523570899.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1392/hunter-hopkins/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1659/theresa-hubbard/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1659/theresa-hubbard/" itemprop="name">Theresa Hubbard</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1659/theresa-hubbard/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1995/nadine-mastrogiacomo/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1995/nadine-mastrogiacomo/" itemprop="name">Nadine Mastrogiacomo</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1995/nadine-mastrogiacomo/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1425/caitlin-osborne/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1425/caitlin-osborne/" itemprop="name">Caitlin Osborne</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1425/caitlin-osborne/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1857/brittany-pickwoad/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1857/brittany-pickwoad/" itemprop="name">Brittany Pickwoad</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1857/brittany-pickwoad/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1753/chloe-pietrandrea/"><div class="photo" style="background-image:url(\'/photos/recruiters/1561487555.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1753/chloe-pietrandrea/" itemprop="name">Chloe Pietrandrea</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1561487555.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1753/chloe-pietrandrea/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1917/liza-pilande/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1917/liza-pilande/" itemprop="name">Liza Pilande</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1917/liza-pilande/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1320/caitlin-pullen/"><div class="photo" style="background-image:url(\'/photos/recruiters/1507249831.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1320/caitlin-pullen/" itemprop="name">Caitlin Pullen</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1507249831.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1320/caitlin-pullen/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1561/kayana-quick/"><div class="photo" style="background-image:url(\'/photos/recruiters/1524756873.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1561/kayana-quick/" itemprop="name">Kayana Quick</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1524756873.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1561/kayana-quick/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1751/jessica-regester/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1751/jessica-regester/" itemprop="name">Jessica Regester</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1751/jessica-regester/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1660/allison-schreiber/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1660/allison-schreiber/" itemprop="name">Allison Schreiber</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1660/allison-schreiber/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1276/erin-sills/"><div class="photo" style="background-image:url(\'/photos/recruiters/1509743514.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1276/erin-sills/" itemprop="name">Erin  Sills</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1509743514.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1276/erin-sills/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1616/justin-terlaga/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1616/justin-terlaga/" itemprop="name">Justin Terlaga</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1616/justin-terlaga/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1858/jessica-townsend/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1858/jessica-townsend/" itemprop="name">Jessica Townsend</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1858/jessica-townsend/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1284/sky-woroszylo/"><div class="photo" style="background-image:url(\'/photos/recruiters/1507839933.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1284/sky-woroszylo/" itemprop="name">Sky Woroszylo</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Host Healthcare, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1507839933.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1284/sky-woroszylo/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2196/ken-pierce/"><div class="photo" style="background-image:url(\'/photos/recruiters/1580833173.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2196/ken-pierce/" itemprop="name">Ken Pierce</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ingenium Plus Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1580833173.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2196/ken-pierce/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2249/arun-jeldi/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2249/arun-jeldi/" itemprop="name">Arun  Jeldi</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ink staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2249/arun-jeldi/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2209/courtney-williams/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2209/courtney-williams/" itemprop="name">Courtney Williams </a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ink staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2209/courtney-williams/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/168/tanya-furnia/"><div class="photo" style="background-image:url(\'/photos/recruiters/1559320846.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/168/tanya-furnia/" itemprop="name">Tanya Furnia</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Jackson Nurse Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							10 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1559320846.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/168/tanya-furnia/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1421/melda-hanratty/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1421/melda-hanratty/" itemprop="name">Melda Hanratty</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Jackson Nurse Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1421/melda-hanratty/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/340/pam-heimann/"><div class="photo" style="background-image:url(\'/photos/recruiters/1441313334.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/340/pam-heimann/" itemprop="name">Pam Heimann</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Jackson Nurse Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							3 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1441313334.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/340/pam-heimann/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1416/mackenzie-johnson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1559325132.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1416/mackenzie-johnson/" itemprop="name">Mackenzie Johnson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Jackson Nurse Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1559325132.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1416/mackenzie-johnson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/174/jordin-lamkin/"><div class="photo" style="background-image:url(\'/photos/recruiters/1433449208.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/174/jordin-lamkin/" itemprop="name">Jordin Lamkin</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Jackson Nurse Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1433449208.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/174/jordin-lamkin/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/179/jorge-magluta/"><div class="photo" style="background-image:url(\'/photos/recruiters/1433451779.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/179/jorge-magluta/" itemprop="name">Jorge Magluta</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Jackson Nurse Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							8 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1433451779.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/179/jorge-magluta/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1430/cory-major/"><div class="photo" style="background-image:url(\'/photos/recruiters/1516811709.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1430/cory-major/" itemprop="name">Cory Major</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Jackson Nurse Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1516811709.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1430/cory-major/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1420/kevin-mcbean/"><div class="photo" style="background-image:url(\'/photos/recruiters/1559324741.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1420/kevin-mcbean/" itemprop="name">Kevin McBean</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Jackson Nurse Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1559324741.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1420/kevin-mcbean/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1415/ryan-miller/"><div class="photo" style="background-image:url(\'/photos/recruiters/1516383311.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1415/ryan-miller/" itemprop="name">Ryan Miller</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Jackson Nurse Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1516383311.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1415/ryan-miller/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1428/shenan-moore/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1428/shenan-moore/" itemprop="name">Shenan Moore</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Jackson Nurse Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1428/shenan-moore/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/167/caitlin-wilson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1442499459.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/167/caitlin-wilson/" itemprop="name">Caitlin Wilson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Jackson Nurse Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1442499459.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/167/caitlin-wilson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1419/kennith-young/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1419/kennith-young/" itemprop="name">Kennith Young</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Jackson Nurse Professionals</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1419/kennith-young/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/757/maria-falco/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/757/maria-falco/" itemprop="name">Maria Falco</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Loyal Source</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/757/maria-falco/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1207/alexandra-mora/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1207/alexandra-mora/" itemprop="name">Alexandra  Mora</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Loyal Source</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1207/alexandra-mora/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/500/thaddeus-becker/"><div class="photo" style="background-image:url(\'/photos/recruiters/1540475470.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/500/thaddeus-becker/" itemprop="name">Thaddeus  Becker</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							9 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1540475470.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/500/thaddeus-becker/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/794/adam-bruch/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537460586.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/794/adam-bruch/" itemprop="name">Adam Bruch</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537460586.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/794/adam-bruch/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1364/cody-charlton/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537460744.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1364/cody-charlton/" itemprop="name">Cody Charlton</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537460744.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1364/cody-charlton/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/494/matt-demmel/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537460874.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/494/matt-demmel/" itemprop="name">Matt Demmel</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							5 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537460874.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/494/matt-demmel/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1690/christina-eele/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537460991.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1690/christina-eele/" itemprop="name">Christina Eele</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537460991.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1690/christina-eele/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/821/tony-franco/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537461109.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/821/tony-franco/" itemprop="name">Tony Franco</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537461109.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/821/tony-franco/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1366/jeff-gordon/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537461388.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1366/jeff-gordon/" itemprop="name">Jeff Gordon</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537461388.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1366/jeff-gordon/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/810/julia-granstrom/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537461837.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/810/julia-granstrom/" itemprop="name">Julia Granstrom</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537461837.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/810/julia-granstrom/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/815/matt-gray/"><div class="photo" style="background-image:url(\'/photos/recruiters/1551371955.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/815/matt-gray/" itemprop="name">Matt Gray</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1551371955.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/815/matt-gray/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1367/jeff-kautz/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537462230.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1367/jeff-kautz/" itemprop="name">Jeff Kautz</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537462230.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1367/jeff-kautz/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1373/malcolm-kindle/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537462364.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1373/malcolm-kindle/" itemprop="name">Malcolm Kindle</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537462364.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1373/malcolm-kindle/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/796/alicia-krepel/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537468803.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/796/alicia-krepel/" itemprop="name">Alicia Krepel</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537468803.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/796/alicia-krepel/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1701/haley-lawrence/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537473103.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1701/haley-lawrence/" itemprop="name">Haley Lawrence</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537473103.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1701/haley-lawrence/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/803/jeff-lutt/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537467071.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/803/jeff-lutt/" itemprop="name">Jeff Lutt</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537467071.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/803/jeff-lutt/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/797/andrew-meyer/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537467236.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/797/andrew-meyer/" itemprop="name">Andrew Meyer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537467236.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/797/andrew-meyer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1375/mike-nanfito/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537468096.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1375/mike-nanfito/" itemprop="name">Mike Nanfito</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537468096.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1375/mike-nanfito/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/808/anne-odell/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537468242.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/808/anne-odell/" itemprop="name">Anne O\'Dell</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537468242.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/808/anne-odell/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1380/wade-paumer/"><div class="photo" style="background-image:url(\'/photos/recruiters/1538504046.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1380/wade-paumer/" itemprop="name">Wade Paumer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1538504046.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1380/wade-paumer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1365/jake-reynoso/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537221522.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1365/jake-reynoso/" itemprop="name">Jake Reynoso</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							6 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537221522.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1365/jake-reynoso/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/497/randi-rowe/"><div class="photo" style="background-image:url(\'/photos/recruiters/1447873639.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/497/randi-rowe/" itemprop="name">Randi Rowe</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1447873639.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/497/randi-rowe/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1362/brian-scobee/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537469026.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1362/brian-scobee/" itemprop="name">Brian Scobee</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537469026.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1362/brian-scobee/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/495/meghan-shanahan/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537218337.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/495/meghan-shanahan/" itemprop="name">Meghan Shanahan</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							9 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537218337.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/495/meghan-shanahan/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1378/steve-steffen/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537469378.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1378/steve-steffen/" itemprop="name">Steve Steffen</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537469378.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1378/steve-steffen/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/490/joe-stillman/"><div class="photo" style="background-image:url(\'/photos/recruiters/1537469478.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/490/joe-stillman/" itemprop="name">Joe Stillman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1537469478.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/490/joe-stillman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2056/sara-tracy/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2056/sara-tracy/" itemprop="name">Sara Tracy</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2056/sara-tracy/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1374/matt-weeks/"><div class="photo" style="background-image:url(\'/photos/recruiters/1546529894.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1374/matt-weeks/" itemprop="name">Matt Weeks</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1546529894.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1374/matt-weeks/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1376/shelby-wilson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1511368362.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1376/shelby-wilson/" itemprop="name">Shelby Wilson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">LRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1511368362.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1376/shelby-wilson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2238/samantha-hage/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2238/samantha-hage/" itemprop="name">Samantha  Hage</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Magnet Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2238/samantha-hage/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2239/chaz-koziol/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2239/chaz-koziol/" itemprop="name">Chaz Koziol</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Magnet Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2239/chaz-koziol/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1996/jason-ross/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581695189.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1996/jason-ross/" itemprop="name">Jason Ross</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Magnet Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							8 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581695189.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1996/jason-ross/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2237/keri-vogt/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2237/keri-vogt/" itemprop="name">Keri Vogt</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Magnet Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2237/keri-vogt/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1775/krystle-zecha/"><div class="photo" style="background-image:url(\'/photos/recruiters/1542299194.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1775/krystle-zecha/" itemprop="name">Krystle  Zecha</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Magnet Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							5 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1542299194.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1775/krystle-zecha/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/475/steve-manning/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/475/steve-manning/" itemprop="name">Steve Manning</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">MAS Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/475/steve-manning/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/11/bill-murray/"><div class="photo" style="background-image:url(\'/photos/recruiters/1425498447.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/11/bill-murray/" itemprop="name">Bill Murray</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">MAS Medical Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1425498447.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/11/bill-murray/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/218/stephanie-balkovec/"><div class="photo" style="background-image:url(\'/photos/recruiters/1435237756.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/218/stephanie-balkovec/" itemprop="name">Stephanie Balkovec</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1435237756.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/218/stephanie-balkovec/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/150/brad-bell/"><div class="photo" style="background-image:url(\'/photos/recruiters/1435241292.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/150/brad-bell/" itemprop="name">Brad Bell</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							15 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1435241292.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/150/brad-bell/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/919/aaron-bogren/"><div class="photo" style="background-image:url(\'/photos/recruiters/1481631335.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/919/aaron-bogren/" itemprop="name">Aaron Bogren</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1481631335.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/919/aaron-bogren/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/156/mark-brezinski/"><div class="photo" style="background-image:url(\'/photos/recruiters/1434139254.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/156/mark-brezinski/" itemprop="name">Mark  Brezinski</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1434139254.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/156/mark-brezinski/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/144/katie-davidson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1481639805.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/144/katie-davidson/" itemprop="name">Katie Davidson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							12 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1481639805.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/144/katie-davidson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/916/austin-deupree/"><div class="photo" style="background-image:url(\'/photos/recruiters/1481579942.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/916/austin-deupree/" itemprop="name">Austin  Deupree</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1481579942.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/916/austin-deupree/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/155/ronnie-eastman/"><div class="photo" style="background-image:url(\'/photos/recruiters/1434137124.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/155/ronnie-eastman/" itemprop="name">Ronnie Eastman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1434137124.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/155/ronnie-eastman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/154/annie-emens/"><div class="photo" style="background-image:url(\'/photos/recruiters/1434562908.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/154/annie-emens/" itemprop="name">Annie Emens</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							6 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1434562908.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/154/annie-emens/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/222/erin-field/"><div class="photo" style="background-image:url(\'/photos/recruiters/1435271935.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/222/erin-field/" itemprop="name">Erin Field</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1435271935.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/222/erin-field/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/925/rhonda-fogle/"><div class="photo" style="background-image:url(\'/photos/recruiters/1482256214.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/925/rhonda-fogle/" itemprop="name">Rhonda Fogle</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1482256214.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/925/rhonda-fogle/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/224/derek-forshee/"><div class="photo" style="background-image:url(\'/photos/recruiters/1434756415.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/224/derek-forshee/" itemprop="name">Derek Forshee</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							7 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1434756415.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/224/derek-forshee/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1001/sharon-hetrick/"><div class="photo" style="background-image:url(\'/photos/recruiters/1486586949.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1001/sharon-hetrick/" itemprop="name">Sharon Hetrick</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							11 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1486586949.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1001/sharon-hetrick/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/920/melissa-hirschauer/"><div class="photo" style="background-image:url(\'/photos/recruiters/1481636312.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/920/melissa-hirschauer/" itemprop="name">Melissa Hirschauer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							3 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1481636312.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/920/melissa-hirschauer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1647/anthony-hudson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1532004638.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1647/anthony-hudson/" itemprop="name">Anthony Hudson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1532004638.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1647/anthony-hudson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/158/meg-hughes/"><div class="photo" style="background-image:url(\'/photos/recruiters/1480538663.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/158/meg-hughes/" itemprop="name">Meg Hughes</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							74 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1480538663.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/158/meg-hughes/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/145/melissa-kersten/"><div class="photo" style="background-image:url(\'/photos/recruiters/1432742927.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/145/melissa-kersten/" itemprop="name">Melissa Kersten</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1432742927.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/145/melissa-kersten/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/912/brian-king/"><div class="photo" style="background-image:url(\'/photos/recruiters/1481574073.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/912/brian-king/" itemprop="name">Brian King</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1481574073.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/912/brian-king/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/914/craig-korth/"><div class="photo" style="background-image:url(\'/photos/recruiters/1481574223.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/914/craig-korth/" itemprop="name">Craig Korth</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1481574223.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/914/craig-korth/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/157/lindsay-levi/"><div class="photo" style="background-image:url(\'/photos/recruiters/1490820173.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/157/lindsay-levi/" itemprop="name">Lindsay Levi</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							15 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1490820173.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/157/lindsay-levi/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/148/todd-linde/"><div class="photo" style="background-image:url(\'/photos/recruiters/1443038684.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/148/todd-linde/" itemprop="name">Todd Linde</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1443038684.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/148/todd-linde/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/151/jenifer-lyman/"><div class="photo" style="background-image:url(\'/photos/recruiters/1492433819.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/151/jenifer-lyman/" itemprop="name">Jenifer Lyman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							8 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1492433819.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/151/jenifer-lyman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/152/mike-mcsorley/"><div class="photo" style="background-image:url(\'/photos/recruiters/1432745159.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/152/mike-mcsorley/" itemprop="name">Mike McSorley</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							6 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1432745159.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/152/mike-mcsorley/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/146/ted-merkin/"><div class="photo" style="background-image:url(\'/photos/recruiters/1432757087.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/146/ted-merkin/" itemprop="name">Ted Merkin</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1432757087.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/146/ted-merkin/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/217/chris-palassis/"><div class="photo" style="background-image:url(\'/photos/recruiters/1481574602.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/217/chris-palassis/" itemprop="name">Chris Palassis</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							12 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1481574602.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/217/chris-palassis/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/149/nick-penders/"><div class="photo" style="background-image:url(\'/photos/recruiters/1435159673.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/149/nick-penders/" itemprop="name">Nick Penders</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1435159673.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/149/nick-penders/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/220/denise-pottorf/"><div class="photo" style="background-image:url(\'/photos/recruiters/1481638197.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/220/denise-pottorf/" itemprop="name">Denise Pottorf</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							6 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1481638197.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/220/denise-pottorf/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/917/rick-schleue/"><div class="photo" style="background-image:url(\'/photos/recruiters/1519922605.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/917/rick-schleue/" itemprop="name">Rick Schleue</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1519922605.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/917/rick-schleue/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/143/scott-schuckman/"><div class="photo" style="background-image:url(\'/photos/recruiters/1432814163.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/143/scott-schuckman/" itemprop="name">Scott Schuckman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1432814163.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/143/scott-schuckman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/913/sapan-shah/"><div class="photo" style="background-image:url(\'/photos/recruiters/1481574143.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/913/sapan-shah/" itemprop="name">Sapan Shah</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							5 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1481574143.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/913/sapan-shah/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1664/mckenna-slack-brady/"><div class="photo" style="background-image:url(\'/photos/recruiters/1534796249.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1664/mckenna-slack-brady/" itemprop="name">McKenna Slack-Brady</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1534796249.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1664/mckenna-slack-brady/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1026/joe-trout/"><div class="photo" style="background-image:url(\'/photos/recruiters/1487686910.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1026/joe-trout/" itemprop="name">Joe Trout</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1487686910.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1026/joe-trout/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/915/brandon-wilson/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/915/brandon-wilson/" itemprop="name">Brandon  Wilson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/915/brandon-wilson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/918/jake-zoucha/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/918/jake-zoucha/" itemprop="name">Jake Zoucha</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Medical Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/918/jake-zoucha/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/381/erika-araujo/"><div class="photo" style="background-image:url(\'/photos/recruiters/1444395736.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/381/erika-araujo/" itemprop="name">Erika  Araujo</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">MedPro Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							13 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1444395736.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/381/erika-araujo/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/385/karen-guarascio/"><div class="photo" style="background-image:url(\'/photos/recruiters/1444060889.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/385/karen-guarascio/" itemprop="name">Karen  Guarascio</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">MedPro Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1444060889.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/385/karen-guarascio/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/656/victor-martinez/"><div class="photo" style="background-image:url(\'/photos/recruiters/1490110299.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/656/victor-martinez/" itemprop="name">Victor Martinez</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">MedPro Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							13 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1490110299.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/656/victor-martinez/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/383/camesha-pitterson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1443797357.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/383/camesha-pitterson/" itemprop="name">Camesha  Pitterson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">MedPro Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							11 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1443797357.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/383/camesha-pitterson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/388/ronald-weeks/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/388/ronald-weeks/" itemprop="name">Ronald  Weeks</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">MedPro Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/388/ronald-weeks/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/745/veronica-baita/"><div class="photo" style="background-image:url(\'/photos/recruiters/1470944895.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/745/veronica-baita/" itemprop="name">Veronica Baita</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">National Staffing Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							17 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1470944895.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/745/veronica-baita/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/747/zander-santacroce/"><div class="photo" style="background-image:url(\'/photos/recruiters/1470170863.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/747/zander-santacroce/" itemprop="name">Zander Santacroce</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">National Staffing Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1470170863.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/747/zander-santacroce/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/743/tyler-wright/"><div class="photo" style="background-image:url(\'/photos/recruiters/1471263374.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/743/tyler-wright/" itemprop="name">Tyler Wright</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">National Staffing Solutions</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							7 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1471263374.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/743/tyler-wright/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1954/jody-critser/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1954/jody-critser/" itemprop="name">Jody Critser</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nationwide Nurses</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1954/jody-critser/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1263/tonya-hollenbeck/"><div class="photo" style="background-image:url(\'/photos/recruiters/1503622576.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1263/tonya-hollenbeck/" itemprop="name">Tonya Hollenbeck</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nationwide Nurses</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							3 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1503622576.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1263/tonya-hollenbeck/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1317/jessica-mattox/"><div class="photo" style="background-image:url(\'/photos/recruiters/1506651919.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1317/jessica-mattox/" itemprop="name">Jessica Mattox</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nationwide Nurses</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1506651919.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1317/jessica-mattox/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2177/rolf-parrenas/"><div class="photo" style="background-image:url(\'/photos/recruiters/1579908983.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2177/rolf-parrenas/" itemprop="name">Rolf Parrenas</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Noor Staffing Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							3 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1579908983.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2177/rolf-parrenas/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2176/danny-rios/"><div class="photo" style="background-image:url(\'/photos/recruiters/1580162259.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2176/danny-rios/" itemprop="name">Danny Rios</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Noor Staffing Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							6 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1580162259.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2176/danny-rios/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2130/tobin-jacobson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1578068716.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2130/tobin-jacobson/" itemprop="name">Tobin Jacobson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurse 2 Nurse Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1578068716.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2130/tobin-jacobson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2186/jeremy-jones/"><div class="photo" style="background-image:url(\'/photos/recruiters/1580238517.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2186/jeremy-jones/" itemprop="name">Jeremy Jones</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurse 2 Nurse Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1580238517.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2186/jeremy-jones/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2129/megan-jones/"><div class="photo" style="background-image:url(\'/photos/recruiters/1576161293.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2129/megan-jones/" itemprop="name">Megan Jones</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurse 2 Nurse Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1576161293.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2129/megan-jones/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2131/mary-jones-rn/"><div class="photo" style="background-image:url(\'/photos/recruiters/1578068626.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2131/mary-jones-rn/" itemprop="name">Mary Jones, RN</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurse 2 Nurse Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							3 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1578068626.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2131/mary-jones-rn/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2185/nikaela-raney/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2185/nikaela-raney/" itemprop="name">Nikaela Raney</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurse 2 Nurse Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2185/nikaela-raney/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2171/lisa-arent/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2171/lisa-arent/" itemprop="name">Lisa Arent</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2171/lisa-arent/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/678/amy-berg/"><div class="photo" style="background-image:url(\'/photos/recruiters/1580480749.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/678/amy-berg/" itemprop="name">Amy Berg</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1580480749.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/678/amy-berg/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2175/kimberly-blom/"><div class="photo" style="background-image:url(\'/photos/recruiters/1579700700.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2175/kimberly-blom/" itemprop="name">Kimberly Blom</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1579700700.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2175/kimberly-blom/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2173/tali-caiani/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2173/tali-caiani/" itemprop="name">Tali Caiani</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2173/tali-caiani/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2190/kristie-christopherson/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2190/kristie-christopherson/" itemprop="name">Kristie Christopherson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2190/kristie-christopherson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/677/rhianna-deering/"><div class="photo" style="background-image:url(\'/photos/recruiters/1462802649.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/677/rhianna-deering/" itemprop="name">Rhianna Deering</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1462802649.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/677/rhianna-deering/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2178/john-gratton/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2178/john-gratton/" itemprop="name">John Gratton</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2178/john-gratton/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1454/phillip-herman/"><div class="photo" style="background-image:url(\'/photos/recruiters/1518539512.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1454/phillip-herman/" itemprop="name">Phillip  Herman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1518539512.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1454/phillip-herman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2182/ashley-montasser/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2182/ashley-montasser/" itemprop="name">Ashley Montasser</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2182/ashley-montasser/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2179/eden-mueller/"><div class="photo" style="background-image:url(\'/photos/recruiters/1579804184.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2179/eden-mueller/" itemprop="name">Eden Mueller</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1579804184.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2179/eden-mueller/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2189/amber-nelson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1580480959.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2189/amber-nelson/" itemprop="name">Amber Nelson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1580480959.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2189/amber-nelson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2194/lisa-pramod/"><div class="photo" style="background-image:url(\'/photos/recruiters/1580500440.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2194/lisa-pramod/" itemprop="name">Lisa Pramod</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1580500440.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2194/lisa-pramod/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/653/andrea-rupnow/"><div class="photo" style="background-image:url(\'/photos/recruiters/1466620839.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/653/andrea-rupnow/" itemprop="name">Andrea Rupnow</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							12 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1466620839.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/653/andrea-rupnow/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2172/chailey-thomson/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2172/chailey-thomson/" itemprop="name">Chailey Thomson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2172/chailey-thomson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2169/jacob-weber/"><div class="photo" style="background-image:url(\'/photos/recruiters/1579560755.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2169/jacob-weber/" itemprop="name">Jacob Weber</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Nurses PRN</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1579560755.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2169/jacob-weber/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2081/tanya-groff/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2081/tanya-groff/" itemprop="name">Tanya Groff</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">NuWest Group </span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2081/tanya-groff/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2082/shelby-mcintosh/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2082/shelby-mcintosh/" itemprop="name">Shelby McIntosh</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">NuWest Group </span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2082/shelby-mcintosh/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2141/kayla-cash/"><div class="photo" style="background-image:url(\'/photos/recruiters/1578331542.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2141/kayla-cash/" itemprop="name">Kayla Cash</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">OneStaff Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							3 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1578331542.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2141/kayla-cash/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2140/denise-christensen/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2140/denise-christensen/" itemprop="name">Denise Christensen</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">OneStaff Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2140/denise-christensen/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/93/joelle-chandler/"><div class="photo" style="background-image:url(\'/photos/recruiters/1431980831.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/93/joelle-chandler/" itemprop="name">Joelle Chandler</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Onward HealthCare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1431980831.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/93/joelle-chandler/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1609/stephanie-brown/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1609/stephanie-brown/" itemprop="name">Stephanie Brown</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Premier Medical Staffing Services</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1609/stephanie-brown/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1803/kelly-foley/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1803/kelly-foley/" itemprop="name">Kelly Foley</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Premier Medical Staffing Services</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1803/kelly-foley/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1608/amanda-hanson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1529423797.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1608/amanda-hanson/" itemprop="name">Amanda Hanson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Premier Medical Staffing Services</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1529423797.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1608/amanda-hanson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2005/sarah-koeppel/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2005/sarah-koeppel/" itemprop="name">Sarah Koeppel</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Premier Medical Staffing Services</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2005/sarah-koeppel/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1628/kenda-qualman/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1628/kenda-qualman/" itemprop="name">Kenda Qualman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Premier Medical Staffing Services</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1628/kenda-qualman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2225/levi-anderson/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2225/levi-anderson/" itemprop="name">Levi Anderson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2225/levi-anderson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2226/melissa-bunz/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2226/melissa-bunz/" itemprop="name">Melissa Bunz</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2226/melissa-bunz/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2222/kris-carlson/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2222/kris-carlson/" itemprop="name">Kris Carlson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2222/kris-carlson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2227/nick-cruickshank/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2227/nick-cruickshank/" itemprop="name">Nick Cruickshank</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2227/nick-cruickshank/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2208/dave-finkin/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2208/dave-finkin/" itemprop="name">Dave Finkin</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2208/dave-finkin/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2202/jared-friesen/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581457140.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2202/jared-friesen/" itemprop="name">Jared Friesen</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581457140.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2202/jared-friesen/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2223/kyle-gregory/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2223/kyle-gregory/" itemprop="name">Kyle Gregory</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2223/kyle-gregory/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2212/aaron-haffke/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581791185.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2212/aaron-haffke/" itemprop="name">Aaron Haffke</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581791185.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2212/aaron-haffke/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2205/kenzie-haffke/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581366612.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2205/kenzie-haffke/" itemprop="name">Kenzie Haffke</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581366612.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2205/kenzie-haffke/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2229/tabby-hinkle/"><div class="photo" style="background-image:url(\'/photos/recruiters/1582137039.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2229/tabby-hinkle/" itemprop="name">Tabby Hinkle</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1582137039.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2229/tabby-hinkle/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2204/josh-kastner/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581366149.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2204/josh-kastner/" itemprop="name">Josh Kastner</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581366149.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2204/josh-kastner/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2220/jordan-kuhl/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2220/jordan-kuhl/" itemprop="name">Jordan Kuhl</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2220/jordan-kuhl/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2217/chaney-laux/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2217/chaney-laux/" itemprop="name">Chaney Laux</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2217/chaney-laux/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2228/ryan-lemrick/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2228/ryan-lemrick/" itemprop="name">Ryan Lemrick</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2228/ryan-lemrick/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2206/todd-linde/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581367821.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2206/todd-linde/" itemprop="name">Todd Linde</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581367821.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2206/todd-linde/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2210/dustin-lower/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581523335.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2210/dustin-lower/" itemprop="name">Dustin Lower</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581523335.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2210/dustin-lower/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2224/kyle-mahoney/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2224/kyle-mahoney/" itemprop="name">Kyle Mahoney</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2224/kyle-mahoney/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2218/cindy-muma/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2218/cindy-muma/" itemprop="name">Cindy Muma</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2218/cindy-muma/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2213/abby-petersen/"><div class="photo" style="background-image:url(\'/photos/recruiters/1582221904.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2213/abby-petersen/" itemprop="name">Abby Petersen</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1582221904.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2213/abby-petersen/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2211/michael-procopio/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581523620.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2211/michael-procopio/" itemprop="name">Michael Procopio</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581523620.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2211/michael-procopio/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2219/jacob-sexton/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581704464.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2219/jacob-sexton/" itemprop="name">Jacob Sexton</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581704464.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2219/jacob-sexton/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2216/brett-steinacher/"><div class="photo" style="background-image:url(\'/photos/recruiters/1582034099.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2216/brett-steinacher/" itemprop="name">Brett Steinacher</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1582034099.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2216/brett-steinacher/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2215/brennan-stessman/"><div class="photo" style="background-image:url(\'/photos/recruiters/1582137227.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2215/brennan-stessman/" itemprop="name">Brennan Stessman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1582137227.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2215/brennan-stessman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2240/jeffrey-stillinger/"><div class="photo" style="background-image:url(\'/photos/recruiters/1582131823.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2240/jeffrey-stillinger/" itemprop="name">Jeffrey  Stillinger</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1582131823.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2240/jeffrey-stillinger/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2221/josh-van-roekel/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2221/josh-van-roekel/" itemprop="name">Josh Van Roekel</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2221/josh-van-roekel/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2214/ashley-webb/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2214/ashley-webb/" itemprop="name">Ashley Webb</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Prime Time Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2214/ashley-webb/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1180/kyle-anzalone/"><div class="photo" style="background-image:url(\'/photos/recruiters/1499115147.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1180/kyle-anzalone/" itemprop="name">Kyle Anzalone</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1499115147.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1180/kyle-anzalone/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/793/jaclyn-carroll/"><div class="photo" style="background-image:url(\'/photos/recruiters/1475771000.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/793/jaclyn-carroll/" itemprop="name">Jaclyn Carroll</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1475771000.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/793/jaclyn-carroll/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1073/ryan-gallagher/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1073/ryan-gallagher/" itemprop="name">Ryan  Gallagher</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1073/ryan-gallagher/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1216/jess-haungs/"><div class="photo" style="background-image:url(\'/photos/recruiters/1498243580.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1216/jess-haungs/" itemprop="name">Jess Haungs</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1498243580.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1216/jess-haungs/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1704/branden-hodges/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1704/branden-hodges/" itemprop="name">Branden Hodges</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1704/branden-hodges/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/251/rob-horton/"><div class="photo" style="background-image:url(\'/photos/recruiters/1435850982.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/251/rob-horton/" itemprop="name">Rob Horton</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1435850982.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/251/rob-horton/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1940/ashley-hunter/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1940/ashley-hunter/" itemprop="name">ASHLEY Hunter</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1940/ashley-hunter/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1770/erin-johnson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1542037957.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1770/erin-johnson/" itemprop="name">Erin Johnson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1542037957.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1770/erin-johnson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1384/perry-littlejohn/"><div class="photo" style="background-image:url(\'/photos/recruiters/1511529000.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1384/perry-littlejohn/" itemprop="name">Perry Littlejohn</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1511529000.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1384/perry-littlejohn/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/600/samantha-lovell/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/600/samantha-lovell/" itemprop="name">Samantha Lovell</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/600/samantha-lovell/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/252/holly-nadaud/"><div class="photo" style="background-image:url(\'/photos/recruiters/1435841010.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/252/holly-nadaud/" itemprop="name">Holly Nadaud</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1435841010.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/252/holly-nadaud/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1212/tim-palmer/"><div class="photo" style="background-image:url(\'/photos/recruiters/1500944928.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1212/tim-palmer/" itemprop="name">Tim Palmer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1500944928.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1212/tim-palmer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/639/olivia-quinn/"><div class="photo" style="background-image:url(\'/photos/recruiters/1474981517.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/639/olivia-quinn/" itemprop="name">Olivia Quinn</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1474981517.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/639/olivia-quinn/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2068/gabe-rivera/"><div class="photo" style="background-image:url(\'/photos/recruiters/1568901388.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2068/gabe-rivera/" itemprop="name">Gabe Rivera</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1568901388.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2068/gabe-rivera/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1661/amir-sadri/"><div class="photo" style="background-image:url(\'/photos/recruiters/1533663250.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1661/amir-sadri/" itemprop="name">Amir Sadri</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1533663250.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1661/amir-sadri/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2002/paulina-schwartsman/"><div class="photo" style="background-image:url(\'/photos/recruiters/1562606927.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2002/paulina-schwartsman/" itemprop="name">Paulina Schwartsman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1562606927.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2002/paulina-schwartsman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1772/sima-schwartsman/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1772/sima-schwartsman/" itemprop="name">Sima Schwartsman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1772/sima-schwartsman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1555/anna-siver/"><div class="photo" style="background-image:url(\'/photos/recruiters/1523638476.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1555/anna-siver/" itemprop="name">Anna Siver</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1523638476.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1555/anna-siver/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1771/jennifer-visnovits/"><div class="photo" style="background-image:url(\'/photos/recruiters/1542040565.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1771/jennifer-visnovits/" itemprop="name">Jennifer Visnovits</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1542040565.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1771/jennifer-visnovits/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1432/kyle-walsh/"><div class="photo" style="background-image:url(\'/photos/recruiters/1516991003.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1432/kyle-walsh/" itemprop="name">Kyle Walsh</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1516991003.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1432/kyle-walsh/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1773/chelsey-welton/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1773/chelsey-welton/" itemprop="name">Chelsey Welton</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1773/chelsey-welton/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1074/megan-williams/"><div class="photo" style="background-image:url(\'/photos/recruiters/1491850982.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1074/megan-williams/" itemprop="name">Megan Williams</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">ProLink Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1491850982.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1074/megan-williams/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1228/barbie-bell/"><div class="photo" style="background-image:url(\'/photos/recruiters/1499820459.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1228/barbie-bell/" itemprop="name">Barbie Bell</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Protocol Agency</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1499820459.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1228/barbie-bell/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2230/steven-bowie/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2230/steven-bowie/" itemprop="name">Steven Bowie</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Protocol Agency</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2230/steven-bowie/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2110/veloria-dyer/"><div class="photo" style="background-image:url(\'/photos/recruiters/1574190591.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2110/veloria-dyer/" itemprop="name">Veloria Dyer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Protocol Agency</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1574190591.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2110/veloria-dyer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2109/michael-goodman/"><div class="photo" style="background-image:url(\'/photos/recruiters/1574182535.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2109/michael-goodman/" itemprop="name">Michael Goodman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Protocol Agency</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1574182535.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2109/michael-goodman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2080/demekia-ulerio/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2080/demekia-ulerio/" itemprop="name">Demekia Ulerio</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Protocol Agency</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2080/demekia-ulerio/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2107/montana-bret/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2107/montana-bret/" itemprop="name">Montana Bret</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Pulse Clinical Alliance</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2107/montana-bret/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2108/patrick-higgins/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2108/patrick-higgins/" itemprop="name">Patrick HIGGINS</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Pulse Clinical Alliance</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2108/patrick-higgins/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1259/lindsay-vela/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1259/lindsay-vela/" itemprop="name">Lindsay Vela</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Pulse Clinical Alliance</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1259/lindsay-vela/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/24/amanda-belloff/"><div class="photo" style="background-image:url(\'/photos/recruiters/1429128131.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/24/amanda-belloff/" itemprop="name">Amanda Belloff</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Randstad Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1429128131.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/24/amanda-belloff/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/924/clevon-john/"><div class="photo" style="background-image:url(\'/photos/recruiters/1482246111.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/924/clevon-john/" itemprop="name">Clevon  John</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Randstad Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1482246111.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/924/clevon-john/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2102/iesha-johnson/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2102/iesha-johnson/" itemprop="name">Iesha Johnson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Randstad Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2102/iesha-johnson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2058/kia-round/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2058/kia-round/" itemprop="name">Kia Round</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Randstad Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2058/kia-round/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2201/ashley-starnes/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2201/ashley-starnes/" itemprop="name">Ashley Starnes</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Randstad Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2201/ashley-starnes/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2232/nicole-blowe/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581620151.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2232/nicole-blowe/" itemprop="name">Nicole Blowe</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">RCM HealthCare Travel</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581620151.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2232/nicole-blowe/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2235/chelsea-coddington/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581619892.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2235/chelsea-coddington/" itemprop="name">Chelsea Coddington</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">RCM HealthCare Travel</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581619892.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2235/chelsea-coddington/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2233/katrina-fogel/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581620943.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2233/katrina-fogel/" itemprop="name">Katrina Fogel</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">RCM HealthCare Travel</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581620943.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2233/katrina-fogel/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2231/scott-gaumond/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581621484.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2231/scott-gaumond/" itemprop="name">Scott Gaumond</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">RCM HealthCare Travel</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581621484.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2231/scott-gaumond/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2234/mia-lagang/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2234/mia-lagang/" itemprop="name">Mia  Lagang</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">RCM HealthCare Travel</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2234/mia-lagang/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2236/jessica-regester/"><div class="photo" style="background-image:url(\'/photos/recruiters/1581622715.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2236/jessica-regester/" itemprop="name">Jessica Regester</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">RCM HealthCare Travel</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1581622715.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2236/jessica-regester/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/164/dixie-bostick/"><div class="photo" style="background-image:url(\'/photos/recruiters/1433451462.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/164/dixie-bostick/" itemprop="name">Dixie Bostick</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Remede Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							7 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1433451462.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/164/dixie-bostick/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1916/sonya-hughes/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1916/sonya-hughes/" itemprop="name">Sonya Hughes</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Remede Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1916/sonya-hughes/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1915/victoria-lynch/"><div class="photo" style="background-image:url(\'/photos/recruiters/1553023884.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1915/victoria-lynch/" itemprop="name">Victoria Lynch</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Remede Group</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1553023884.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1915/victoria-lynch/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/894/brannen-betz/"><div class="photo" style="background-image:url(\'/photos/recruiters/1480531298.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/894/brannen-betz/" itemprop="name">Brannen Betz</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Skyline Med Staff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1480531298.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/894/brannen-betz/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2128/kennedy-corbin/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2128/kennedy-corbin/" itemprop="name">Kennedy Corbin</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Skyline Med Staff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2128/kennedy-corbin/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2127/marisa-dawson/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2127/marisa-dawson/" itemprop="name">Marisa Dawson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Skyline Med Staff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2127/marisa-dawson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/895/brenda-dreier/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/895/brenda-dreier/" itemprop="name">Brenda Dreier</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Skyline Med Staff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/895/brenda-dreier/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/775/todd-green/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/775/todd-green/" itemprop="name">Todd Green</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Skyline Med Staff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/775/todd-green/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1862/bert-huebert/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1862/bert-huebert/" itemprop="name">Bert Huebert</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Skyline Med Staff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1862/bert-huebert/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/774/kelsey-martin/"><div class="photo" style="background-image:url(\'/photos/recruiters/1480526577.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/774/kelsey-martin/" itemprop="name">Kelsey Martin</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Skyline Med Staff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1480526577.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/774/kelsey-martin/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2126/emily-reynolds/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2126/emily-reynolds/" itemprop="name">Emily Reynolds</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Skyline Med Staff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2126/emily-reynolds/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1123/amy-robbins/"><div class="photo" style="background-image:url(\'/photos/recruiters/1499718669.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1123/amy-robbins/" itemprop="name">Amy Robbins</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Skyline Med Staff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1499718669.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1123/amy-robbins/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2116/jonathan-barnes/"><div class="photo" style="background-image:url(\'/photos/recruiters/1574778688.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2116/jonathan-barnes/" itemprop="name">Jonathan Barnes</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Smarter Healthcare Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1574778688.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2116/jonathan-barnes/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2089/corey-seeley/"><div class="photo" style="background-image:url(\'/photos/recruiters/1571767930.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2089/corey-seeley/" itemprop="name">Corey Seeley</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Smarter Healthcare Partners</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1571767930.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2089/corey-seeley/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2165/jennifer-fuller/"><div class="photo" style="background-image:url(\'/photos/recruiters/1579191502.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2165/jennifer-fuller/" itemprop="name">Jennifer Fuller</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Source Medical Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1579191502.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2165/jennifer-fuller/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1942/cari-mcwilliams/"><div class="photo" style="background-image:url(\'/photos/recruiters/1579191702.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1942/cari-mcwilliams/" itemprop="name">Cari McWilliams</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Source Medical Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1579191702.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1942/cari-mcwilliams/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2248/stephanie-plechas/"><div class="photo" style="background-image:url(\'/photos/recruiters/1582663443.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2248/stephanie-plechas/" itemprop="name">Stephanie Plechas</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Source Medical Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1582663443.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2248/stephanie-plechas/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2009/david-grijalva/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2009/david-grijalva/" itemprop="name">David Grijalva</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Supplemental Health Care</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2009/david-grijalva/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2180/sade-reid/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2180/sade-reid/" itemprop="name">Sade Reid</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Synergy Staffing Inc</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2180/sade-reid/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1723/elyse-spraul/"><div class="photo" style="background-image:url(\'/photos/recruiters/1539788064.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1723/elyse-spraul/" itemprop="name">Elyse Spraul</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Tailored Healthcare Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1539788064.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1723/elyse-spraul/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1897/brittany-chalupa/"><div class="photo" style="background-image:url(\'/photos/recruiters/1551192323.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1897/brittany-chalupa/" itemprop="name">Brittany Chalupa</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TaleMed</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1551192323.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1897/brittany-chalupa/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1408/serena-jacobs/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1408/serena-jacobs/" itemprop="name">Serena Jacobs</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TaleMed</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1408/serena-jacobs/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1677/lana-kimmich/"><div class="photo" style="background-image:url(\'/photos/recruiters/1536096048.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1677/lana-kimmich/" itemprop="name">Lana Kimmich</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TaleMed</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							17 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1536096048.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1677/lana-kimmich/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1765/ben-rascona/"><div class="photo" style="background-image:url(\'/photos/recruiters/1542116790.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1765/ben-rascona/" itemprop="name">Ben Rascona</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TaleMed</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							4 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1542116790.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1765/ben-rascona/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1306/nick-seibert/"><div class="photo" style="background-image:url(\'/photos/recruiters/1505752146.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1306/nick-seibert/" itemprop="name">Nick Seibert</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TaleMed</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1505752146.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1306/nick-seibert/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1298/shannen-slaughter/"><div class="photo" style="background-image:url(\'/photos/recruiters/1547732794.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1298/shannen-slaughter/" itemprop="name">Shannen Slaughter</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TaleMed</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1547732794.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1298/shannen-slaughter/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1162/robby-williams/"><div class="photo" style="background-image:url(\'/photos/recruiters/1505753037.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1162/robby-williams/" itemprop="name">Robby Williams</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TaleMed</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1505753037.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1162/robby-williams/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1339/jolyn-carrion/"><div class="photo" style="background-image:url(\'/photos/recruiters/1513616980.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1339/jolyn-carrion/" itemprop="name">Jolyn Carrion</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Therapia Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1513616980.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1339/jolyn-carrion/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1887/theresa-irizarry/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1887/theresa-irizarry/" itemprop="name">Theresa Irizarry</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Therapia Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1887/theresa-irizarry/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1876/gaby-martinez/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1876/gaby-martinez/" itemprop="name">Gaby Martinez</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Therapia Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1876/gaby-martinez/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1768/russell-maurer/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1768/russell-maurer/" itemprop="name">Russell Maurer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Therapia Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1768/russell-maurer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1338/shannon-taylor/"><div class="photo" style="background-image:url(\'/photos/recruiters/1513185760.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1338/shannon-taylor/" itemprop="name">Shannon  Taylor</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Therapia Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1513185760.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1338/shannon-taylor/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2167/olivia-anderson/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2167/olivia-anderson/" itemprop="name">Olivia Anderson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2167/olivia-anderson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1982/bailey-bennett/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1982/bailey-bennett/" itemprop="name">Bailey Bennett</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1982/bailey-bennett/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1936/jay-bernal/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1936/jay-bernal/" itemprop="name">Jay Bernal</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1936/jay-bernal/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2065/kevin-chambers/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2065/kevin-chambers/" itemprop="name">Kevin Chambers</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2065/kevin-chambers/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2166/chase-chartier/"><div class="photo" style="background-image:url(\'/photos/recruiters/1582300066.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2166/chase-chartier/" itemprop="name">Chase Chartier</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1582300066.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2166/chase-chartier/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/238/tammy-corwin/"><div class="photo" style="background-image:url(\'/photos/recruiters/1438188452.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/238/tammy-corwin/" itemprop="name">Tammy Corwin</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1438188452.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/238/tammy-corwin/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/628/nicky-cyr/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/628/nicky-cyr/" itemprop="name">Nicky Cyr</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/628/nicky-cyr/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/645/tami-eastridge/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/645/tami-eastridge/" itemprop="name">Tami  Eastridge</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/645/tami-eastridge/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2067/shelby-graham/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2067/shelby-graham/" itemprop="name">Shelby Graham</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2067/shelby-graham/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1514/sarah-kalina/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1514/sarah-kalina/" itemprop="name">Sarah Kalina</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1514/sarah-kalina/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1440/duval-kamara/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1440/duval-kamara/" itemprop="name">Duval Kamara</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1440/duval-kamara/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2125/janita-luedtke/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2125/janita-luedtke/" itemprop="name">Janita Luedtke</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2125/janita-luedtke/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/780/kalin-lueth/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/780/kalin-lueth/" itemprop="name">Kalin Lueth</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/780/kalin-lueth/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2168/chris-magnussen/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2168/chris-magnussen/" itemprop="name">Chris Magnussen</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2168/chris-magnussen/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/415/sarah-mason/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/415/sarah-mason/" itemprop="name">Sarah Mason</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/415/sarah-mason/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1786/susan-meyer/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1786/susan-meyer/" itemprop="name">Susan Meyer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1786/susan-meyer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1241/angela-muhle/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1241/angela-muhle/" itemprop="name">Angela Muhle</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1241/angela-muhle/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2122/julie-phelps/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2122/julie-phelps/" itemprop="name">Julie Phelps</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2122/julie-phelps/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1579/tim-sabaliauskas/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1579/tim-sabaliauskas/" itemprop="name">Tim Sabaliauskas</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1579/tim-sabaliauskas/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1509/edward-shobe/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1509/edward-shobe/" itemprop="name">Edward Shobe</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1509/edward-shobe/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2121/normandy-smith/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2121/normandy-smith/" itemprop="name">Normandy Smith</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2121/normandy-smith/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1934/jason-stavropoulos/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1934/jason-stavropoulos/" itemprop="name">Jason Stavropoulos</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1934/jason-stavropoulos/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2066/tristan-thoms/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2066/tristan-thoms/" itemprop="name">Tristan Thoms</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2066/tristan-thoms/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1508/amber-welchert/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1508/amber-welchert/" itemprop="name">Amber Welchert</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1508/amber-welchert/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2123/iris-white/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2123/iris-white/" itemprop="name">Iris White</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2123/iris-white/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1513/monica-wichman/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1513/monica-wichman/" itemprop="name">Monica Wichman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Titan Medical</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1513/monica-wichman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/565/joey-balestrieri/"><div class="photo" style="background-image:url(\'/photos/recruiters/1454517725.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/565/joey-balestrieri/" itemprop="name">Joey Balestrieri</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1454517725.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/565/joey-balestrieri/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/841/brandan-benzing/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/841/brandan-benzing/" itemprop="name">Brandan Benzing</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/841/brandan-benzing/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/923/timothy-bruce/"><div class="photo" style="background-image:url(\'/photos/recruiters/1482281684.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/923/timothy-bruce/" itemprop="name">Timothy Bruce</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1482281684.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/923/timothy-bruce/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/570/boe-casarez/"><div class="photo" style="background-image:url(\'/photos/recruiters/1454603321.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/570/boe-casarez/" itemprop="name">Boe Casarez</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1454603321.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/570/boe-casarez/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/845/jordan-cummings/"><div class="photo" style="background-image:url(\'/photos/recruiters/1477493108.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/845/jordan-cummings/" itemprop="name">Jordan Cummings</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1477493108.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/845/jordan-cummings/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/528/josh-cutchins/"><div class="photo" style="background-image:url(\'/photos/recruiters/1449613665.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/528/josh-cutchins/" itemprop="name">Josh Cutchins</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							5 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1449613665.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/528/josh-cutchins/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1486/alexis-dunbar/"><div class="photo" style="background-image:url(\'/photos/recruiters/1519420925.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1486/alexis-dunbar/" itemprop="name">Alexis Dunbar</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1519420925.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1486/alexis-dunbar/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/563/trent-foss/"><div class="photo" style="background-image:url(\'/photos/recruiters/1468938556.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/563/trent-foss/" itemprop="name">Trent Foss</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1468938556.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/563/trent-foss/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/114/tom-horan/"><div class="photo" style="background-image:url(\'/photos/recruiters/1432232229.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/114/tom-horan/" itemprop="name">Tom Horan</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							8 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1432232229.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/114/tom-horan/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/735/tj-hulbert/"><div class="photo" style="background-image:url(\'/photos/recruiters/1468937082.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/735/tj-hulbert/" itemprop="name">TJ Hulbert</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1468937082.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/735/tj-hulbert/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/569/anna-jeffries/"><div class="photo" style="background-image:url(\'/photos/recruiters/1454618725.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/569/anna-jeffries/" itemprop="name">Anna Jeffries</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							8 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1454618725.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/569/anna-jeffries/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/526/cameron-jeglum/"><div class="photo" style="background-image:url(\'/photos/recruiters/1449612282.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/526/cameron-jeglum/" itemprop="name">Cameron Jeglum</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							5 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1449612282.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/526/cameron-jeglum/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/521/alyssa-kaspar/"><div class="photo" style="background-image:url(\'/photos/recruiters/1541008017.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/521/alyssa-kaspar/" itemprop="name">Alyssa  Kaspar</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							16 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1541008017.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/521/alyssa-kaspar/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/564/westscot-krieger/"><div class="photo" style="background-image:url(\'/photos/recruiters/1454527164.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/564/westscot-krieger/" itemprop="name">Westscot Krieger</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1454527164.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/564/westscot-krieger/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/847/logan-lockard/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/847/logan-lockard/" itemprop="name">Logan Lockard</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/847/logan-lockard/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/572/logan-malueg/"><div class="photo" style="background-image:url(\'/photos/recruiters/1454538931.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/572/logan-malueg/" itemprop="name">Logan Malueg</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							5 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1454538931.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/572/logan-malueg/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/786/aaron-moore/"><div class="photo" style="background-image:url(\'/photos/recruiters/1487088402.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/786/aaron-moore/" itemprop="name">Aaron Moore</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							5 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1487088402.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/786/aaron-moore/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/739/jesse-naumann/"><div class="photo" style="background-image:url(\'/photos/recruiters/1468953011.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/739/jesse-naumann/" itemprop="name">Jesse Naumann</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1468953011.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/739/jesse-naumann/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/562/cameron-peterson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1454520269.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/562/cameron-peterson/" itemprop="name">Cameron Peterson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							5 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1454520269.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/562/cameron-peterson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/166/amy-regazzi/"><div class="photo" style="background-image:url(\'/photos/recruiters/1433189990.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/166/amy-regazzi/" itemprop="name">Amy Regazzi</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1433189990.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/166/amy-regazzi/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/522/lindsey-thowless/"><div class="photo" style="background-image:url(\'/photos/recruiters/1455137516.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/522/lindsey-thowless/" itemprop="name">Lindsey Thowless</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							11 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1455137516.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/522/lindsey-thowless/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/842/laura-wenninger/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/842/laura-wenninger/" itemprop="name">Laura Wenninger</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/842/laura-wenninger/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/529/paul-witthuhn/"><div class="photo" style="background-image:url(\'/photos/recruiters/1449613538.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/529/paul-witthuhn/" itemprop="name">Paul  Witthuhn</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1449613538.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/529/paul-witthuhn/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/737/reece-zoelle/"><div class="photo" style="background-image:url(\'/photos/recruiters/1468954632.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/737/reece-zoelle/" itemprop="name">Reece Zoelle</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TotalMed Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1468954632.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/737/reece-zoelle/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/402/rebecca-baxter/"><div class="photo" style="background-image:url(\'/photos/recruiters/1443472000.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/402/rebecca-baxter/" itemprop="name">Rebecca Baxter</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurse Across America</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1443472000.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/402/rebecca-baxter/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1627/tana-burd-babcock/"><div class="photo" style="background-image:url(\'/photos/recruiters/1530546430.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1627/tana-burd-babcock/" itemprop="name">Tana Burd-Babcock</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurse Across America</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1530546430.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1627/tana-burd-babcock/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/397/olivia-carper/"><div class="photo" style="background-image:url(\'/photos/recruiters/1495118973.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/397/olivia-carper/" itemprop="name">Olivia Carper</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurse Across America</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1495118973.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/397/olivia-carper/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/392/april-coltran/"><div class="photo" style="background-image:url(\'/photos/recruiters/1443472372.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/392/april-coltran/" itemprop="name">April Coltran</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurse Across America</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1443472372.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/392/april-coltran/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/396/gena-deaton/"><div class="photo" style="background-image:url(\'/photos/recruiters/1443472434.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/396/gena-deaton/" itemprop="name">Gena Deaton</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurse Across America</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1443472434.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/396/gena-deaton/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/394/donna-dickson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1443472528.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/394/donna-dickson/" itemprop="name">Donna Dickson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurse Across America</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1443472528.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/394/donna-dickson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/403/james-dwyer/"><div class="photo" style="background-image:url(\'/photos/recruiters/1443472568.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/403/james-dwyer/" itemprop="name">James Dwyer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurse Across America</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1443472568.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/403/james-dwyer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1722/ashley-hazen/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1722/ashley-hazen/" itemprop="name">Ashley Hazen</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurse Across America</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1722/ashley-hazen/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/393/derek-king/"><div class="photo" style="background-image:url(\'/photos/recruiters/1443472723.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/393/derek-king/" itemprop="name">Derek  King</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurse Across America</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1443472723.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/393/derek-king/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/398/karena-schellpeper/"><div class="photo" style="background-image:url(\'/photos/recruiters/1443472856.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/398/karena-schellpeper/" itemprop="name">Karena Schellpeper</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurse Across America</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1443472856.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/398/karena-schellpeper/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/400/teresa-tiao/"><div class="photo" style="background-image:url(\'/photos/recruiters/1443473123.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/400/teresa-tiao/" itemprop="name">Teresa Tiao</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurse Across America</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1443473123.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/400/teresa-tiao/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2115/madison-bassett/"><div class="photo" style="background-image:url(\'/photos/recruiters/1574697424.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2115/madison-bassett/" itemprop="name">Madison Bassett</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurses, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1574697424.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2115/madison-bassett/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1792/shelby-ewing/"><div class="photo" style="background-image:url(\'/photos/recruiters/1544817938.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1792/shelby-ewing/" itemprop="name">Shelby  Ewing</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurses, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1544817938.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1792/shelby-ewing/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1638/shane-garner/"><div class="photo" style="background-image:url(\'/photos/recruiters/1532532489.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1638/shane-garner/" itemprop="name">Shane Garner</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurses, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							3 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1532532489.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1638/shane-garner/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1636/tori-goff/"><div class="photo" style="background-image:url(\'/photos/recruiters/1534424493.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1636/tori-goff/" itemprop="name">Tori  Goff</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurses, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							3 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1534424493.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1636/tori-goff/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2161/justin-park/"><div class="photo" style="background-image:url(\'/photos/recruiters/1579020609.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2161/justin-park/" itemprop="name">Justin Park</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurses, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1579020609.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2161/justin-park/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2054/melissa-pritchard/"><div class="photo" style="background-image:url(\'/photos/recruiters/1579018669.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2054/melissa-pritchard/" itemprop="name">Melissa Pritchard</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurses, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1579018669.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2054/melissa-pritchard/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2083/alexis-rhoden/"><div class="photo" style="background-image:url(\'/photos/recruiters/1579018577.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2083/alexis-rhoden/" itemprop="name">Alexis Rhoden</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurses, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1579018577.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2083/alexis-rhoden/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2160/sebastian-simota/"><div class="photo" style="background-image:url(\'/photos/recruiters/1579018528.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2160/sebastian-simota/" itemprop="name">Sebastian Simota</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurses, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1579018528.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2160/sebastian-simota/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2181/emma-tribble/"><div class="photo" style="background-image:url(\'/photos/recruiters/1580241708.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2181/emma-tribble/" itemprop="name">Emma Tribble</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Travel Nurses, Inc.</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1580241708.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2181/emma-tribble/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/312/jesse-adams/"><div class="photo" style="background-image:url(\'/photos/recruiters/1439569763.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/312/jesse-adams/" itemprop="name">Jesse Adams</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1439569763.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/312/jesse-adams/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1945/veronica-bugueno/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1945/veronica-bugueno/" itemprop="name">Veronica Bugueno</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1945/veronica-bugueno/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1944/michael-clark/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1944/michael-clark/" itemprop="name">Michael Clark</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1944/michael-clark/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1999/jack-douglas/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1999/jack-douglas/" itemprop="name">Jack Douglas</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1999/jack-douglas/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/320/audrey-futryk/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/320/audrey-futryk/" itemprop="name">Audrey Futryk</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/320/audrey-futryk/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1237/katie-hinkle/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1237/katie-hinkle/" itemprop="name">Katie Hinkle</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1237/katie-hinkle/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/314/abby-keiss/"><div class="photo" style="background-image:url(\'/photos/recruiters/1439500627.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/314/abby-keiss/" itemprop="name">Abby Keiss</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1439500627.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/314/abby-keiss/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1946/blake-king/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1946/blake-king/" itemprop="name">Blake King</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1946/blake-king/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1998/lydia-phillippe/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1998/lydia-phillippe/" itemprop="name">Lydia Phillippe</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1998/lydia-phillippe/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/318/sarah-price/"><div class="photo" style="background-image:url(\'/photos/recruiters/1439491903.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/318/sarah-price/" itemprop="name">Sarah Price</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1439491903.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/318/sarah-price/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2000/cory-queen/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2000/cory-queen/" itemprop="name">Cory Queen</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2000/cory-queen/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/311/robyn-reinig/"><div class="photo" style="background-image:url(\'/photos/recruiters/1439500127.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/311/robyn-reinig/" itemprop="name">Robyn Reinig</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1439500127.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/311/robyn-reinig/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/308/jason-sagehorn/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/308/jason-sagehorn/" itemprop="name">Jason Sagehorn</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/308/jason-sagehorn/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/853/alicia-stoller/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_female.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/853/alicia-stoller/" itemprop="name">Alicia Stoller</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_female.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/853/alicia-stoller/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1652/meg-taylor/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1652/meg-taylor/" itemprop="name">Meg Taylor</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Triage Staffing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1652/meg-taylor/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/364/robin-clark/"><div class="photo" style="background-image:url(\'/photos/recruiters/1442593436.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/364/robin-clark/" itemprop="name">Robin Clark</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1442593436.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/364/robin-clark/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/363/regina-davis/"><div class="photo" style="background-image:url(\'/photos/recruiters/1442593295.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/363/regina-davis/" itemprop="name">Regina Davis</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1442593295.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/363/regina-davis/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/369/virginia-johnson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1442600019.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/369/virginia-johnson/" itemprop="name">Virginia Johnson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1442600019.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/369/virginia-johnson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/361/jan-steele/"><div class="photo" style="background-image:url(\'/photos/recruiters/1532106127.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/361/jan-steele/" itemprop="name">Jan Steele</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">TRS Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1532106127.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/361/jan-steele/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/90/heather-bors/"><div class="photo" style="background-image:url(\'/photos/recruiters/1527615340.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/90/heather-bors/" itemprop="name">Heather Bors</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							2 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1527615340.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/90/heather-bors/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/83/bridgette-coletta/"><div class="photo" style="background-image:url(\'/photos/recruiters/1525204217.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/83/bridgette-coletta/" itemprop="name">Bridgette Coletta</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1525204217.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/83/bridgette-coletta/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1992/sam-feyka/"><div class="photo" style="background-image:url(\'/photos/recruiters/1561391460.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1992/sam-feyka/" itemprop="name">Sam Feyka</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1561391460.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1992/sam-feyka/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1567/jerry-helferich/"><div class="photo" style="background-image:url(\'/photos/recruiters/1525189338.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1567/jerry-helferich/" itemprop="name">Jerry Helferich</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1525189338.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1567/jerry-helferich/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/88/matt-krentz/"><div class="photo" style="background-image:url(\'/photos/recruiters/1525188747.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/88/matt-krentz/" itemprop="name">Matt Krentz</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1525188747.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/88/matt-krentz/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/89/chuck-logsdon/"><div class="photo" style="background-image:url(\'/photos/recruiters/1525188800.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/89/chuck-logsdon/" itemprop="name">Chuck Logsdon</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1525188800.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/89/chuck-logsdon/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1990/jordan-mauri/"><div class="photo" style="background-image:url(\'/photos/recruiters/1560885684.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1990/jordan-mauri/" itemprop="name">Jordan Mauri</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1560885684.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1990/jordan-mauri/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1994/frank-reeder/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1994/frank-reeder/" itemprop="name">Frank Reeder</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1994/frank-reeder/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1988/lorna-roberts/"><div class="photo" style="background-image:url(\'/photos/recruiters/1560800409.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1988/lorna-roberts/" itemprop="name">Lorna Roberts</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1560800409.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1988/lorna-roberts/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/86/sondra-ryle/"><div class="photo" style="background-image:url(\'/photos/recruiters/1529954646.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/86/sondra-ryle/" itemprop="name">Sondra Ryle</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1529954646.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/86/sondra-ryle/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1179/joe-schoepf/"><div class="photo" style="background-image:url(\'/photos/recruiters/1525189080.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1179/joe-schoepf/" itemprop="name">Joe Schoepf</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1525189080.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1179/joe-schoepf/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1178/jase-simpson/"><div class="photo" style="background-image:url(\'/photos/recruiters/1525189148.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1178/jase-simpson/" itemprop="name">Jase Simpson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1525189148.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1178/jase-simpson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1991/bobby-timbo/"><div class="photo" style="background-image:url(\'/photos/recruiters/1560886131.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1991/bobby-timbo/" itemprop="name">Bobby Timbo</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1560886131.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1991/bobby-timbo/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1993/justin-whisenant/"><div class="photo" style="background-image:url(\'/photos/recruiters/1560886884.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1993/justin-whisenant/" itemprop="name">Justin Whisenant</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1560886884.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1993/justin-whisenant/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1989/harry-wittenberg/"><div class="photo" style="background-image:url(\'/photos/recruiters/1561040146.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1989/harry-wittenberg/" itemprop="name">Harry Wittenberg</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">trustaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1561040146.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1989/harry-wittenberg/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2030/tom-boquard/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2030/tom-boquard/" itemprop="name">Tom Boquard</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Trusted Nurse Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2030/tom-boquard/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2031/jess-cramer/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2031/jess-cramer/" itemprop="name">Jess Cramer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Trusted Nurse Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2031/jess-cramer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2026/tristan-ernst/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2026/tristan-ernst/" itemprop="name">Tristan  Ernst</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Trusted Nurse Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2026/tristan-ernst/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2019/drew-harding/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2019/drew-harding/" itemprop="name">Drew Harding</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Trusted Nurse Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2019/drew-harding/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2025/kyle-hartl/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2025/kyle-hartl/" itemprop="name">Kyle  Hartl</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Trusted Nurse Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2025/kyle-hartl/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2027/dan-jackielaszek/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2027/dan-jackielaszek/" itemprop="name">Dan Jackielaszek</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Trusted Nurse Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2027/dan-jackielaszek/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2024/milan-kana/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2024/milan-kana/" itemprop="name">Milan Kana</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Trusted Nurse Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2024/milan-kana/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2028/kyle-mackowiak/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2028/kyle-mackowiak/" itemprop="name">Kyle  Mackowiak</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Trusted Nurse Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2028/kyle-mackowiak/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2022/alex-mcclogin/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2022/alex-mcclogin/" itemprop="name">Alex  McClogin</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Trusted Nurse Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2022/alex-mcclogin/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2021/tim-seel/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2021/tim-seel/" itemprop="name">Tim Seel</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Trusted Nurse Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2021/tim-seel/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2023/neal-smith/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2023/neal-smith/" itemprop="name">Neal  Smith</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Trusted Nurse Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2023/neal-smith/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2029/adam-smithson/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2029/adam-smithson/" itemprop="name">Adam Smithson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Trusted Nurse Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2029/adam-smithson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2020/ed-voll/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2020/ed-voll/" itemprop="name">Ed Voll</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Trusted Nurse Staffing, LLC</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2020/ed-voll/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2098/meagan-farris/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2098/meagan-farris/" itemprop="name">Meagan Farris</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ventura Medstaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2098/meagan-farris/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1853/jacob-hagen/"><div class="photo" style="background-image:url(\'/photos/recruiters/1549549411.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1853/jacob-hagen/" itemprop="name">Jacob Hagen</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ventura Medstaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1549549411.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1853/jacob-hagen/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2097/amie-hare/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2097/amie-hare/" itemprop="name">Amie Hare</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ventura Medstaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2097/amie-hare/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1861/ravi-jaipaul/"><div class="photo" style="background-image:url(\'/photos/recruiters/1549644676.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1861/ravi-jaipaul/" itemprop="name">Ravi Jaipaul</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ventura Medstaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							18 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1549644676.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1861/ravi-jaipaul/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1852/jamie-klein--rn-bsn/"><div class="photo" style="background-image:url(\'/photos/recruiters/1549572197.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1852/jamie-klein--rn-bsn/" itemprop="name">Jamie Klein -RN BSN </a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ventura Medstaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							14 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1549572197.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1852/jamie-klein--rn-bsn/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2099/michelle-libal/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2099/michelle-libal/" itemprop="name">Michelle Libal</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ventura Medstaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2099/michelle-libal/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1860/elsa-meyer/"><div class="photo" style="background-image:url(\'/photos/recruiters/1549564634.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1860/elsa-meyer/" itemprop="name">Elsa Meyer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ventura Medstaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							39 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1549564634.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1860/elsa-meyer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2101/christian-morgan/"><div class="photo" style="background-image:url(\'/photos/recruiters/1572878854.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2101/christian-morgan/" itemprop="name">Christian Morgan</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ventura Medstaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1572878854.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2101/christian-morgan/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2095/jeffrey-noble/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2095/jeffrey-noble/" itemprop="name">Jeffrey Noble</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ventura Medstaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2095/jeffrey-noble/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/4/eric-scheid/"><div class="photo" style="background-image:url(\'/photos/recruiters/1572362420.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/4/eric-scheid/" itemprop="name">Eric Scheid</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ventura Medstaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							81 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1572362420.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/4/eric-scheid/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2100/jay-smith/"><div class="photo" style="background-image:url(\'/photos/recruiters/1572545070.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2100/jay-smith/" itemprop="name">Jay Smith</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ventura Medstaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1572545070.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2100/jay-smith/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2157/todd-thieschafer/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2157/todd-thieschafer/" itemprop="name">Todd Thieschafer</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ventura Medstaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							3 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2157/todd-thieschafer/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2096/mike-wicker/"><div class="photo" style="background-image:url(\'/photos/recruiters/1573254776.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2096/mike-wicker/" itemprop="name">Mike Wicker</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Ventura Medstaff</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							12 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1573254776.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2096/mike-wicker/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1987/michelle-breitburg/"><div class="photo" style="background-image:url(\'/photos/recruiters/1567628494.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1987/michelle-breitburg/" itemprop="name">Michelle  Breitburg</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Vero Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1567628494.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1987/michelle-breitburg/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2051/charles-gibbons/"><div class="photo" style="background-image:url(\'/photos/recruiters/1570133152.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2051/charles-gibbons/" itemprop="name">Charles Gibbons</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Vero Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1570133152.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2051/charles-gibbons/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2103/jennifer-hemmingway/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2103/jennifer-hemmingway/" itemprop="name">Jennifer Hemmingway</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Vero Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2103/jennifer-hemmingway/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2049/kelly-kidder/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2049/kelly-kidder/" itemprop="name">Kelly Kidder</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Vero Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2049/kelly-kidder/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2104/cody-pettit/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2104/cody-pettit/" itemprop="name">Cody Pettit</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Vero Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2104/cody-pettit/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2050/phillip-stovall/"><div class="photo" style="background-image:url(\'/photos/recruiters/1566420500.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2050/phillip-stovall/" itemprop="name">Phillip Stovall</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Vero Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1566420500.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2050/phillip-stovall/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/909/simon-turnheim/"><div class="photo" style="background-image:url(\'/photos/recruiters/1494016046.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/909/simon-turnheim/" itemprop="name">Simon Turnheim</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Vero Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1494016046.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/909/simon-turnheim/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2105/monique-wilkerson/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2105/monique-wilkerson/" itemprop="name">Monique Wilkerson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Vero Travel Nursing</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2105/monique-wilkerson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2250/greg-beebe/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2250/greg-beebe/" itemprop="name">Greg  Beebe</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Voyage Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2250/greg-beebe/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1622/max-berry/"><div class="photo" style="background-image:url(\'/photos/recruiters/1529434293.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1622/max-berry/" itemprop="name">Max  Berry</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Voyage Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1529434293.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1622/max-berry/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1682/doug-davis/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1682/doug-davis/" itemprop="name">Doug Davis</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Voyage Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1682/doug-davis/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2245/rosy-fernandez/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2245/rosy-fernandez/" itemprop="name">Rosy Fernandez</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Voyage Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2245/rosy-fernandez/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1620/michael-gebhardt/"><div class="photo" style="background-image:url(\'/photos/recruiters/1529434345.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1620/michael-gebhardt/" itemprop="name">Michael  Gebhardt</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Voyage Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1529434345.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1620/michael-gebhardt/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2247/jeffery-johnson/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2247/jeffery-johnson/" itemprop="name">Jeffery Johnson</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Voyage Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2247/jeffery-johnson/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1624/stefanie-large/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1624/stefanie-large/" itemprop="name">Stefanie  Large</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Voyage Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1624/stefanie-large/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1681/joshua-lerman/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1681/joshua-lerman/" itemprop="name">Joshua Lerman</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Voyage Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1681/joshua-lerman/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/2246/riley-smith/"><div class="photo" style="background-image:url(\'/photos/recruiters/silhouette_male.png\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/2246/riley-smith/" itemprop="name">Riley Smith</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Voyage Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
																	<div class="star"></div>
															</div>
							0 Reviews						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/silhouette_male.png">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/2246/riley-smith/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1623/tafawn-smith/"><div class="photo" style="background-image:url(\'/photos/recruiters/1529434417.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1623/tafawn-smith/" itemprop="name">Tafawn  Smith</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Voyage Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1529434417.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1623/tafawn-smith/">
					</div>
									
					<div class="recruiter_search_profile_box" itemscope="" itemtype="http://schema.org/Person">
						<a href="/travel-nursing-recruiter/1621/jon-whitehead/"><div class="photo" style="background-image:url(\'/photos/recruiters/1529434623.jpg\');"></div></a>
						
						<div class="name">
							<a href="/travel-nursing-recruiter/1621/jon-whitehead/" itemprop="name">Jon Whitehead</a><br>
							<span class="agency" itemprop="worksFor" itemscope="" itemtype="http://schema.org/Organization"><span itemprop="name">Voyage Healthcare</span></span>
						</div>
						<div class="reviews">
							<div class="stars">
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
																	<div class="star full"></div>
															</div>
							1 Review						</div>
						<div class="clearer"></div>
						
						<meta itemprop="image" content="https://www.travelnursesource.com/photos/recruiters/1529434623.jpg">
						<meta itemprop="url" content="https://www.travelnursesource.com/travel-nursing-recruiter/1621/jon-whitehead/">
					</div>
									<div id="recruiters_pagination">
						<a href="/search-travel-nursing-recruiters/?min_experience=0&amp;max_experience=25&amp;page=1">Show Paginated</a>
					</div>
						</div>';

        $scraper = new Scraper();

        $links = array_unique($scraper->getAllLinksInText($file));

        foreach ($links as $link) {

            if ($scraper->inString($link, 'travel-nursing-recruiter'))

            $html = $scraper->getCurl('https://www.travelnursesource.com'.$link);

            $profileData = $scraper->getInBetween($html, '<div class="name">', '</div>');

            $fullName = $scraper->getInBetween($profileData, '<h1>', '</h1>');
            $agency = $scraper->getInBetween($profileData, '"agency">', '</span>');
            $phone = $scraper->getInBetween($profileData, '"phone">', '</span>');
            $frontEmail = $scraper->getInBetween($profileData, 'data-user="', '" data-domain');
            $endEmail = $scraper->getInBetween($profileData, 'data-domain="', '"></a>');

            $email = $frontEmail.'@'.$endEmail;

            $newLead = ScrapedLeads::firstOrNew(['email'=> $email]);

            $newLead->full_name = $fullName;
            $newLead->agency = $agency;
            $newLead->phone = $phone;
            $newLead->email = $email;

            $newLead->save();

            sleep(10);
        }


    }
}