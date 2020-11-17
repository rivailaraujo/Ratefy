const data = {
  "artists" : "Pearl Jam",
  "href" : "https://api.spotify.com/v1/albums/5B4PYA7wNN4WdEXdIJu58a",
  "images" : "https://i.scdn.co/image/425e1fef6d70a48a103dfd168a556b7f72f72865",
  "name" : "Ten",
  "tracks" : [
    {
      "duration_ms" : 231367,
      "spotify" : "https://open.spotify.com/track/4nRyBgsqXEP2oPfzaMeZr7",
      "name" : "Once",
      "track_number" : 1
    }, {
      "duration_ms" : 292580,
      "spotify" : "https://open.spotify.com/track/6QewNVIDKdSl8Y3ycuHIei",
      "name" : "Even Flow",
      "track_number" : 2
    }, {
      "duration_ms" : 340907,
      "explicit" : false,
      "spotify" : "https://open.spotify.com/track/1L94M3KIu7QluZe63g64rv",
      "name" : "Alive",
      "track_number" : 3
    }, {
      "duration_ms" : 200039,
      "spotify" : "https://open.spotify.com/track/337kipThk7s7nHJ8POg1jK",
      "name" : "Why Go",
      "track_number" : 4
    }, {
      "duration_ms" : 342653,
      "spotify" : "https://open.spotify.com/track/5Xak5fmy089t0FYmh3VJiY",
      "name" : "Black",
      "track_number" : 5
    }, {
      "duration_ms" : 318226,
      "spotify" : "https://open.spotify.com/track/62nQ8UZVqR2RMvkJHkcO2o",
      "name" : "Jeremy",
      "track_number" : 6
    }, {
      "duration_ms" : 161893,
      "spotify" : "https://open.spotify.com/track/0LBmvPJYmtEJ7kkWvc3kbT",
      "name" : "Oceans",
      "track_number" : 7
    }, {
      "duration_ms" : 210520,
      "spotify" : "https://open.spotify.com/track/3zNkNyXEeDs2HeAZ0fYqxI",
      "name" : "Porch",
      "track_number" : 8
    }, {
      "duration_ms" : 298933,
      "spotify" : "https://open.spotify.com/track/3KcJEOyaLDLAERlOCBhmPA",
      "name" : "Garden",
      "track_number" : 9
    }, {
      "duration_ms" : 258186,
      "spotify" : "https://open.spotify.com/track/4tfWxyTBCJoN5gK358oL3W",
      "name" : "Deep",
      "track_number" : 10
    }, {
      "duration_ms" : 546018,
      "spotify" : "https://open.spotify.com/track/3xAka0D8585mDoShALnsd7",
      "name" : "Release",
      "track_number" : 11
    } 
  ]
};

// helper method
function convertToHumanTime(duration) {
  let s = parseInt((duration / 1000) % 60, 10);
  const m = parseInt((duration / (1000 * 60)) % 60, 10);

  s = (s < 10) ? `0${s}` : s;

  return `${m}:${s}`;
}

// methods to create and render the album info
function createMarkupAlbum(data) {
  return (`
    <img class="album-image" src="${data.images}" alt="${data.name}">
    <p class="album-title">${data.name}</p>
    <p class="album-artist">${data.artists}</p>
    <p class="album-counter">${data.tracks.length} Músicas</p>
  `);
}

function renderAlbumInfo(data, element) {
  const markup = createMarkupAlbum(data);
  element.innerHTML = markup;
};

// methods to create and render the tracks
function createMarkupTracks(tracks) {
  return tracks.map(track => `
    <div class="music">
      <p class="music-number">${track.track_number}</p>
      <p class="music-title">${track.name}</p>
      <p class="music-duration">${convertToHumanTime(track.duration_ms)}</p>
    </div>`).join('');
}

function renderAlbumTracks(data, element) {
  const markup = createMarkupTracks(data);
  element.innerHTML = markup;
}

// working with the DOM and calling the methods
const list = document.getElementById('list');
const album = document.getElementById('album');

renderAlbumTracks(data.tracks, list);

renderAlbumInfo(data, album);