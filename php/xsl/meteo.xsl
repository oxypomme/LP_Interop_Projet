<?xml version='1.0' encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:param name="date" />
  <xsl:param name="time" />
  <xsl:output method="html" encoding="UTF-8" indent="yes" />
  <xsl:strip-space elements="*" />

  <xsl:template match="/">
    <div class="meteo--list">
      <xsl:apply-templates select="previsions/echeance" />
    </div>
  </xsl:template>

  <!-- Render HTML element -->
  <xsl:template match="echeance" mode="render">
    <div>
      <!-- Getting expired item -->
      <xsl:attribute name="class">
        <xsl:choose>
          <xsl:when test="number(substring(@timestamp, 12, 2)) &lt; number($time)">
              <xsl:value-of select="'meteo--item item-passed'"/>
          </xsl:when>
          <xsl:otherwise>
            <xsl:value-of select="'meteo--item'"/>
          </xsl:otherwise>
        </xsl:choose>
      </xsl:attribute>
      <!-- Heure -->
      <div class="meteo--item_time">
        <xsl:value-of select="substring(@timestamp, 6, 11)" />
      </div>
      <!-- Température -->
      <xsl:variable name="temperature">
          <xsl:value-of select="round(temperature/level[@val='2m'] - 273.15)"/>
      </xsl:variable>
      <div>
        <!-- Setting class & icon in function of value -->
        <xsl:choose>
          <!-- Very Hot -->
          <xsl:when test="$temperature &gt; 30">
            <xsl:attribute name="class">
              <xsl:value-of select="'meteo--item_temperature t-very-hot'"/>
            </xsl:attribute>
            <i class="fas fa-thermometer-full"></i>
          </xsl:when>
          <!-- Hot -->
          <xsl:when test="$temperature &gt; 20">
            <xsl:attribute name="class">
              <xsl:value-of select="'meteo--item_temperature t-hot'"/>
            </xsl:attribute>
            <i class="fas fa-thermometer-three-quarters"></i>
          </xsl:when>
          <!-- Normal -->
          <xsl:when test="$temperature &gt; 10">
            <xsl:attribute name="class">
              <xsl:value-of select="'meteo--item_temperature'"/>
            </xsl:attribute>
            <i class="fas fa-thermometer-half"></i>
          </xsl:when>
          <!-- Cold -->
          <xsl:when test="$temperature &gt; 0">
            <xsl:attribute name="class">
              <xsl:value-of select="'meteo--item_temperature t-cold'"/>
            </xsl:attribute>
            <i class="fas fa-thermometer-quarter"></i>
          </xsl:when>
          <!-- Very Cold -->
          <xsl:otherwise>
            <xsl:attribute name="class">
              <xsl:value-of select="'meteo--item_temperature t-very-cold'"/>
            </xsl:attribute>
            <i class="fas fa-thermometer-empty"></i>
          </xsl:otherwise>
        </xsl:choose>
        <!-- Value -->
        <xsl:value-of select="$temperature" />
      </div>
      <!-- Status (Pluie, Soleil, Neige) -->
      <div class="meteo--item_status">
        <xsl:choose>
          <!-- Pluie -->
          <xsl:when test="pluie &gt; 0">
            <i class="fas fa-cloud-rain"></i>
          </xsl:when>
          <!-- Soleil -->
          <xsl:otherwise>
            <i class="fas fa-sun"></i>
          </xsl:otherwise>
        </xsl:choose>
        <!-- Neige -->
        <xsl:choose>
          <xsl:when test="risque_neige = 'oui'">
            <i class="fas fa-snowflake"></i>
          </xsl:when>
        </xsl:choose>
      </div>
      <!-- Humidité -->
      <div>
        <xsl:attribute name="class">
          <!-- Setting class in function of value -->
          <xsl:choose>
            <!-- Humide -->
            <xsl:when test="humidite/level &gt; 75">
              <xsl:value-of select="'meteo--item_humid h-humid'"/>
            </xsl:when>
            <!-- Sec -->
            <xsl:otherwise>
              <xsl:value-of select="'meteo--item_humid'"/>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:attribute>
        <!-- Value -->
        <i class="fas fa-tint"></i>
        <xsl:value-of select="humidite/level" />
      </div>
      <!-- Conseils -->
      <div class="meteo--item_advice">
        <i class="far fa-lightbulb">
          <xsl:attribute name="title">
            <xsl:choose>
              <xsl:when test="pluie > 0">
                <xsl:choose>
                  <!-- Pluie + Froid -->
                  <xsl:when test="$temperature &lt; 20">Tu dois vraiment sortir ?</xsl:when>
                  <!-- Pluie + Chaud -->
                  <xsl:otherwise>Sort la veste et prend un parapluie dans le sac.</xsl:otherwise>
                </xsl:choose>
              </xsl:when>
              <xsl:otherwise>
                  <xsl:choose>
                    <!-- Soleil + Froid -->
                    <xsl:when test="$temperature &lt; 20">Prend un bonnet... On sait jamais...</xsl:when>
                    <!-- Soleil + Chaud -->
                    <xsl:otherwise>Fonce !</xsl:otherwise>
                  </xsl:choose>
                </xsl:otherwise>
            </xsl:choose>
          </xsl:attribute>
        </i>
      </div>
    </div>
  </xsl:template>
  <!-- Various echances -->
  <xsl:template match="echeance">
    <xsl:variable name="datetime">
      <xsl:value-of select="substring(@timestamp, 0, 17)" />
    </xsl:variable>

    <xsl:choose>
      <xsl:when test="
        $datetime=concat($date, ' 04:00')
        or $datetime=concat($date, ' 07:00')
        or $datetime=concat($date, ' 13:00')
        or $datetime=concat($date, ' 16:00')
        or $datetime=concat($date, ' 19:00')
        or $datetime=concat($date, ' 05:00')
        or $datetime=concat($date, ' 08:00')
        or $datetime=concat($date, ' 14:00')
        or $datetime=concat($date, ' 17:00')
        or $datetime=concat($date, ' 20:00')
      ">
        <xsl:apply-templates select="." mode="render" />
      </xsl:when>
    </xsl:choose>
  </xsl:template>
</xsl:stylesheet>
